<template>
	<div v-for="(category, index) in categories" :key="index">
		<h2 class="item-category-ecommerce">{{category.name}}</h2>
		<a :href="'/categories/home/' + category.id" class="item-category-ecommerce">Ver más</a>

		<section class="container-card-grid">
			<product-card :products="category.products.slice(0, 4)" />
		</section>
	</div>
</template>

<script>
	import { ref, onMounted } from "vue";
	import ProductCard from "../Product/ProductCard.vue";
	export default {
		components: {
			ProductCard,
		},
		// props: [],
		setup(/* props */) {
			const categories = ref(null);

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

			onMounted(() => getCategories());
			return { categories, getCategories };
		},
	};
</script>

<style>
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
