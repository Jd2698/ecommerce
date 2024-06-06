<script setup>
	import { ref, onMounted } from "vue";
	import { addObject, getObject } from "@/helpers/LocalStorage";
	const props = defineProps(["product", "user"]);

	const user_data = ref({});

	const validarSesion = () => {
		if (!props.user) {
			Swal.fire({
				title: "¿Iniciar sesión?",
				text: "Para agregar productos necesita iniciar sesión",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#367932",
				cancelButtonColor: "#d33",
				confirmButtonText: "Iniciar sesión",
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = "/login";
				}
			});
		} else {
			const p = {
				id: props.product.id,
				name: props.product.name,
				description: props.product.description,
				price: props.product.price,
				stock: props.product.stock,
				file: props.product.file,
				quantity: 1,
				subtotal: props.product.price,
			};

			const cart = getObject(user_data.value.id);

			if (cart) {
				if (cart.some((product) => p.id == product.id)) {
					const pro = cart.find((product) => product.id == p.id);

					pro.quantity = pro.quantity + 1;
					pro.subtotal = pro.quantity * pro.price;
				} else {
					cart.push(p);
				}
				addObject(user_data.value.id, cart);
			} else {
				addObject(user_data.value.id, [p]);
			}

			Swal.fire({
				position: "top-end",
				icon: "success",
				title: "se agregó al carrito",
				showConfirmButton: false,
				timer: 800,
			});
		}
	};

	onMounted(() => {
		if (props.user) {
			user_data.value = JSON.parse(props.user);
		}
	});
</script>

<template>
	<div class="container-shop">
		<section class="container-shop-item">
			<div class="shop-item_container-img">
				<img :src="product.file.route" alt="">
			</div>
			<div class="shop-item_container-body">
				<h2>{{product.name}}</h2>
				<p>$ {{product.price}}</p>
				<p>{{product.description}}</p>
				<span>Stock: {{product.stock}}</span>
				<button class="btn border border-black" @click="validarSesion">Add to cart</button>
			</div>
		</section>
	</div>
</template>

<style>
.container-shop {
	display: flex;
	justify-content: center;
}
.container-shop-item {
	box-shadow: 0 3px 4px 0 rgba(120, 120, 120, 0.3);
	width: 600px;
	height: 400px;
	display: flex;
	flex-wrap: wrap;
}
.shop-item_container-img {
	width: 40%;
	height: 100%;
}
.shop-item_container-img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.shop-item_container-body {
	width: 60%;
	padding: 1rem;
	display: flex;
	flex-direction: column;
	justify-content: space-around;
}
</style>
