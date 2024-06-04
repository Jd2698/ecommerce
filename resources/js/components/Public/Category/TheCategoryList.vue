<template>
	<!-- buscador de productos -->
	<div class="mx-3 mb-3 d-flex justify-content-start">
		<div class="input-group border" style="width: 400px;">
			<select v-model="buscadorCategory" class="bg-white border-0" style="width: 30%;">
				<option value="0" class="px-2" dropdown-item>All</option>
				<option :value="category.id" class="px-2" dropdown-item v-for="category in categories" :key="category.id">{{category.name}}</option>
			</select>
			<input v-model="buscador" type="search" class="bg-white py-2 border-0 outline-0" style="width: 70%;" placeholder="Search" @keyup.enter="buscarLibros()" />
		</div>
	</div>

	<!-- productos buscados -->
	<template v-if="products">
		<section class="container-card-grid">
			<product-card :products="products" />
		</section>
	</template>

	<!-- productos con categoría -->
	<template v-else>
		<div v-for="(category, index) in categories" :key="index">
			<h2 class="item-category-ecommerce">{{category.name}}</h2>
			<a :href="'/categories/home/' + category.id" class="item-category-ecommerce">Ver más</a>

			<section class="container-card-grid">
				<product-card :products="category.products.slice(0, 5)" />
			</section>
		</div>
	</template>
</template>

<script setup>
	import { ref, onMounted } from "vue";
	import ProductCard from "../Product/ProductCard.vue";

	const categories = ref(null);
	const buscador = ref(null);
	const buscadorCategory = ref(0);
	const products = ref(null);

	const getCategories = () => {
		axios
			.get(`/categories/home`)
			.then((res) => {
				categories.value = res.data.categories;
			})
			.catch((error) => {
				console.log(error);
			});
	};

	const getProductsBuscador = async () => {
		try {
			const { data } = await axios.get(`/products/buscador`, {
				params: {
					buscador: buscador.value,
					category: buscadorCategory.value,
				},
			});
			products.value = data.products;
		} catch (error) {
			console.log(error);
		}
	};

	const buscarLibros = () => {
		if (!buscador.value == "") {
			getProductsBuscador();
		} else {
			products.value = null;
		}
	};

	onMounted(() => getCategories());
</script>

<style>
input:focus {
	outline: none;
}

.item-category-ecommerce {
	display: inline-block;
	text-decoration: none;
	font-size: 1.5rem;
	padding-left: 1rem;
}
a.item-category-ecommerce {
	font-size: 1rem;
}
</style>
