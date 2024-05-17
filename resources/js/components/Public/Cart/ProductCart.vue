<template>

	<div class="d-flex gap-4 mx-4">
		<li class="list-unstyled container-items-cart">
			<ul v-for="(product, index) in products" :key="index" class="item-cart">
				<img :src="product.file.route" :alt="product.name">
				<div class="cart-body">
					<h4><strong>{{product.name}}</strong></h4>

					<p>{{product.description}}</p>
					<p>Stock: {{product.stock}}</p>
					<p>Subtotal: ${{product.subtotal}}</p>

					<div class="d-flex">
						<button class="p-0 btn" @click="removeProduct(product.id)">
							<i class="fas fa-trash"></i>
							<span class="mx-2">Remove</span>
						</button>
						<div>
							<button class="btn" @click="decreaseProduct(product)"><i class="fas fa-minus"></i></button>
							<span>{{product.quantity}}</span>
							<button class="btn" @click="increaseProduct(product)"><i class="fas fa-plus"></i></button>
						</div>
					</div>
				</div>
			</ul>
		</li>
		<div class="container-info">
			<p>Total: ${{total}}</p>
			<button class="btn btn-success" @click="buyAlert">Realizar compra</button>
		</div>
	</div>
</template>

<script setup>
	import {
		addObject,
		getObject,
		getObjects,
		deleteObject,
		addTotal,
	} from "@/helpers/LocalStorage";

	import { ref, onMounted, computed } from "vue";

	const props = defineProps(["user"]);

	const user_data = ref({});
	const products = ref([]);
	const total = ref(0);

	const decreaseProduct = (product) => {
		if (product.quantity > 1) {
			let newQuantity = product.quantity - 1;
			let newSubtotal = product.price * newQuantity;

			product.quantity = newQuantity;
			product.subtotal = newSubtotal;

			addObject(product, product.id);
			total.value = addTotal();
		}
	};
	const increaseProduct = (product) => {
		if (product.stock > product.quantity) {
			let newQuantity = product.quantity + 1;
			let newSubtotal = product.price * newQuantity;

			product.quantity = newQuantity;
			product.subtotal = newSubtotal;

			addObject(product, product.id);
			total.value = addTotal();
		}
	};

	const removeProduct = (id) => {
		deleteObject(id);
		products.value = getObjects();
		total.value = addTotal();
	};

	const buyAlert = () => {
		Swal.fire({
			title: "¡Aún no está disponible!",
			confirmButtonColor: "#496",
		});
	};

	const index = () => {
		user_data.value = JSON.parse(props.user);
		products.value = getObjects(user_data.value.id);
		// total.value = addTotal();
	};

	onMounted(() => index());
</script>

<style scoped>
.container-items-cart {
	width: 70%;
	display: flex;
	flex-direction: column;
}

.item-cart {
	display: flex;
	gap: 1rem;
	padding: 1.5rem 0;
	border-bottom: 2px solid rgb(208, 206, 206);
}

.item-cart img {
	width: 180px;
	/* height: 100px; */
	object-fit: cover;
	object-position: center;
	border-radius: 0.8rem;
	box-shadow: 2px 2px 5px 2px rgb(199, 199, 199);
}

.container-info {
	width: 30%;
	height: max-content;
	padding: 1rem 2rem;
	box-shadow: 0 0 6px 2px rgb(227, 224, 224);
}
</style>
