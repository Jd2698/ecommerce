<script setup>
	import { ref } from "vue";
	import ProductCard from "./ProductCard.vue";

	const props = defineProps(["categories"]);
	const emit = defineEmits(["updateSearchStatus"]);

	const buscador = ref(null);
	const buscadorCategory =
		props.categories.length != 1 ? ref(0) : ref(props.categories[0].id);
	const products = ref(null);

	const getProducts = async () => {
		try {
			const { data } = await axios.get(`/products/buscador`, {
				params: {
					buscador: buscador.value,
					category: buscadorCategory.value,
				},
			});
			products.value = data.products;
			emit("updateSearchStatus", true);
		} catch (error) {
			console.log(error);
			emit("updateSearchStatus", false);
		}
	};

	const buscarLibros = () => {
		if (!buscador.value == "") {
			getProducts();
		} else {
			products.value = null;
			emit("updateSearchStatus", false);
		}
	};
</script>

<template>
	<!-- buscador de productos -->
	<div class="mx-3 mb-3 d-flex justify-content-start">
		<div class="input-group border" style="width: 400px;">
			<select v-model="buscadorCategory" class="bg-light border-0" style="width: 30%;">
				<option value="0" class="px-2" dropdown-item>All</option>
				<template v-for="category in categories" :key="category.id">
					<option :value="category.id" class="px-2" dropdown-item>{{category.name}}
					</option>
				</template>
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
</template>
