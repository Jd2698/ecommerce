<template>
	<section class="d-flex flex-wrap justify-content-center gap-4 py-4">
		<div v-for="(product, index) in products" :key="index" class="card" style="width: 18rem;">
			<img :src="product.file.route" class="card-img-top" alt="...">
			<div class="card-body">
				<h5 class="card-title">{{product.name}}</h5>
				<p class="card-text">{{product.format_description}}</p>
				<p class="card-text">{{product.price}}</p>
				<a href="#" class="btn btn-primary">Go somewhere</a>
			</div>
		</div>
	</section>
</template>

<script>
	import { ref, onMounted } from "vue";
	export default {
		props: ["category_id"],
		setup(props) {
			const products = ref(null);

			const getProducts = () => {
				axios
					.get(`/products/home/${props.category_id}`)
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
