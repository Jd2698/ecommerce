<template>
	<h2 class="mx-4">{{category.name}}</h2>
	<section class="container-card-grid">
		<product-card :products="products" />
	</section>
</template>

<script>
	import { ref, onMounted } from "vue";
	import ProductCard from "./ProductCard.vue";
	export default {
		components: {
			ProductCard,
		},
		props: ["category"],
		setup(props) {
			const products = ref(null);

			const getProducts = () => {
				axios
					.get(`/products/home/${props.category.id}`)
					.then((res) => {
						products.value = res.data.products;
					})
					.catch((error) => {
						console.log(error);
					});
			};

			onMounted(() => getProducts());

			return { products, getProducts };
		},
	};
</script>
