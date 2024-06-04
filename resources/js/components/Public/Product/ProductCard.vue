<template>
	<template v-for="(product, index) in products" :key="index">
		<a :href="'/products/'+product.id" class="item-card-ecommerce">
			<img :src="product.file.route" :alt="product.name">
			<div class="p-2">
				<p class="">$ {{product.price}}</p>
				<h5 class="">{{product.format_name}}</h5>
			</div>
		</a>
	</template>
</template>

<script setup>
	const props = defineProps(["products"]);
</script>
