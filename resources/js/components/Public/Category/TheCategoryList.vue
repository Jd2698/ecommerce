<template>
	<!-- search component -->
	<template v-if="categoriesStatus">
		<product-search @updateSearchStatus="updateSearchStatus" :categories="categories" />
	</template>

	<!-- productos con categoría -->
	<template v-if="!searchStatus">
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
	import ProductSearch from "../Product/ProductSearch.vue";

	const categories = ref(null);
	const searchStatus = ref(false);
	const categoriesStatus = ref(false);

	const getCategories = () => {
		axios
			.get(`/categories/home`)
			.then((res) => {
				categories.value = res.data.categories;
				categoriesStatus.value = true;
			})
			.catch((error) => {
				console.log(error);
			});
	};

	const updateSearchStatus = (value) => {
		searchStatus.value = value;
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
