<script setup>
	import { ref, onMounted, computed } from "vue";
	import {
		addObject,
		getObject,
		getProducts,
		deleteObject,
		addTotal,
	} from "@/helpers/LocalStorage";

	const props = defineProps(["user"]);

	const user_data = ref(JSON.parse(props.user));
	const products = ref(getProducts(user_data.value.id));
	const total = ref(addTotal(user_data.value.id));

	const decreaseProduct = (product) => {
		if (product.quantity > 1) {
			product.quantity = product.quantity - 1;
			product.subtotal = product.price * product.quantity;

			products.value = products.value.map((p) =>
				p.id == product.id ? { ...p, ...product } : p
			);

			addObject(user_data.value.id, products.value);
			total.value = addTotal(user_data.value.id);
		}
	};

	const increaseProduct = (product) => {
		if (product.stock > product.quantity) {
			product.quantity = product.quantity + 1;
			product.subtotal = product.price * product.quantity;

			products.value = products.value.map((p) =>
				p.id == product.id ? { ...p, ...product } : p
			);

			addObject(user_data.value.id, products.value);
			total.value = addTotal(user_data.value.id);
		}
	};

	const removeProduct = (productId) => {
		products.value = products.value.filter((p) => p.id != productId);

		addObject(user_data.value.id, products.value);
		total.value = addTotal(user_data.value.id);
	};

	const cleanCart = (productId) => {
		deleteObject(user_data.value.id);

		products.value = [];
		total.value = 0;
	};

	const buyAlert = () => {
		Swal.fire({
			title: "¡Aún no está disponible!",
			confirmButtonColor: "#496",
		});
	};
</script>

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
			<div class="btn-group" style="width: 100%">
				<button class="btn btn-light" @click="buyAlert">Realizar compra</button>
				<button class="btn btn-dark" @click="cleanCart(user_data.id)">Limpiar carrito</button>
			</div>
		</div>
	</div>
</template>


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
