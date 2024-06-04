<template>
	<h2 class="mx-4">{{category.name}}</h2>

	<div class="mx-4 input-group rounded border" style="width: 400px;">
		<input v-model="buscador" type="search" class="p-2 bg-white rounded border-0" style="width: 100%;" placeholder="Search" @keyup="buscarLibros" />
	</div>

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
			const buscador = ref("");
			const setTimeoutBuscador = ref(null);

			onMounted(() => getProducts());

			const getProducts = async () => {
				try {
					const { data } = await axios.get(
						`/products/home/${props.category.id}`
					);
					products.value = data.products;
				} catch (error) {
					console.log(error);
				}
			};

			const getProductsBuscador = async () => {
				try {
					const { data } = await axios.get(`/products/buscador`, {
						params: {
							buscador: buscador.value,
							category: props.category.id,
						},
					});
					products.value = data.products;
				} catch (error) {
					console.log(error);
				}
			};

			const buscarLibros = () => {
				clearTimeout(setTimeoutBuscador.value);

				setTimeoutBuscador.value = setTimeout(() => {
					if (buscador.value === "") {
						getProducts();
					} else {
						getProductsBuscador();
					}
				}, 360);
			};

			return { products, getProducts, buscador, buscarLibros };
		},
	};
</script>
