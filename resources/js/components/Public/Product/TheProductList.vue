<template>
	<h2 class="mx-4">{{category.name}}</h2>

	<!-- search component -->
	<product-search @updateSearchStatus="updateSearchStatus" :categories="[category]" />

	<template v-if="!searchStatus">
		<section class="container-card-grid">
			<product-card :products="products" />
		</section>
	</template>
</template>

<script setup>
	import { ref, onMounted } from "vue";
	import ProductCard from "./ProductCard.vue";
	import ProductSearch from "./ProductSearch.vue";
	const props = defineProps(["category"]);

	const products = ref(null);
	const searchStatus = ref(false);

	const updateSearchStatus = (value) => {
		searchStatus.value = value;
	};

	const getProducts = async () => {
		try {
			const { data } = await axios.get(`/products/home/${props.category.id}`);
			products.value = data.products;
		} catch (error) {
			console.log(error);
		}
	};

	onMounted(() => getProducts());
</script>
