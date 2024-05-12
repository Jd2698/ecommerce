<template>
	<a :href="'/products/'+product.id" v-for="(product, index) in products" :key="index" class="item-card-ecommerce">
		<img :src="product.file.route" class="card-img-top" :alt="product.name">
		<div class="p-2">
			<p class="">$ {{product.price}}</p>
			<h5 class="">{{product.format_name}}</h5>
		</div>
	</a>
</template>

<script>
	import { ref, onMounted } from "vue";
	export default {
		props: ["products"],
		setup(props) {
			// onMounted(() => getProducts());

			return {};
		},
	};
</script>
