<template>
	<div class="modal fade" id="product_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{is_create ? 'crear':'editar'}} product</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
				</div>

				<Form @submit="saveProduct" :validation-schema="schema" ref="form">
					<div class="modal-body">
						<section class="row">

							<!-- Show Image -->
							<div class="col-12 d-flex justify-content-center mt-1">
								<img :src="image_preview" alt="Imagen Product" class="img-thumbnail" width="170" height="170">
							</div>

							<!-- Load Image -->
							<div class="col-12 mt-1 ">
								<label for="file" class="form-label">Imagen</label>
								<input type="file" :class="`form-control ${back_errors['file'] ? 'is-invalid' : ''}`" id="file" accept="image/*" @change="previewImage">
								<span class="invalid-feedback" v-if="back_errors['file']">
									{{ back_errors['file'] }}
								</span>
							</div>

							<!-- Name -->
							<div class="col-12">
								<label for="name">Name</label>
								<Field name="name" v-slot="{ errorMessage, field }" v-model="product.name">
									<input type="text" id="name" v-model="product.name" :class="`form-control ${errorMessage || back_errors['name'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['name'] }}</span>
								</Field>
							</div>

							<!-- Price -->
							<div class="col-12 mt-2">
								<Field name="price" v-slot="{ errorMessage, field }" v-model="product.price">
									<label for="price">price</label>
									<input type="text" id="price" v-model="product.price" :class="`form-control ${errorMessage || back_errors['price'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['price'] }}</span>
								</Field>
							</div>

							<!-- Stock -->
							<div class="col-12 mt-2">
								<Field name="stock" v-slot="{ errorMessage, field }" v-model="product.stock">
									<label for="stock">Stock</label>
									<input type="number" id="stock" v-model="product.stock" :class="`form-control ${errorMessage || back_errors['stock'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['stock'] }}</span>
								</Field>
							</div>

							<!-- Description -->
							<div class="col-12 mt-2">
								<Field name="description" v-slot="{ errorMessage, field }" v-model="product.description">
									<label for="description">Description</label>
									<textarea v-model="product.description" :class="`form-control ${errorMessage ? 'is-invalid' : ''}`" id="description" rows="3" v-bind="field"></textarea>
									<span class="invalid-feedback">{{ errorMessage }}</span>
								</Field>
							</div>

							<!-- Category -->
							<div class="col-12 mt-2" v-if="load_category">
								<Field name="category" v-slot="{ errorMessage, field, valid }" v-model="product.category_id">
									<label for="category">Categoria</label>

									<v-select id="category" :options="categories_data" v-model="product.category_id" :reduce="category => category.id" v-bind="field" label="name" placeholder="Selecciona categoria" :clearable="false" :class="`${errorMessage || back_errors['category'] ? 'is-invalid' : ''}`">
									</v-select>
									<span class="invalid-feedback" v-if="!valid">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['category'] }}</span>

								</Field>
							</div>
						</section>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</Form>
			</div>
		</div>
	</div>

</template>

<script>
	import { handlerErrors, successMessage } from "@/helpers/Alerts.js";
	import { ref, getCurrentInstance, computed, onMounted } from "vue";
	import { Field, Form } from "vee-validate";
	import * as yup from "yup";

	export default {
		props: ["product_data"],
		components: { Field, Form },
		setup(props) {
			const instance = getCurrentInstance();

			const is_create = props.product_data ? ref(false) : ref(true);
			const product = is_create.value ? ref({}) : ref(props.product_data);

			const image_preview = is_create.value
				? ref("")
				: ref(product.value.file.route);

			const file = ref(null);
			const back_errors = ref({});

			const load_category = ref(false);
			const categories_data = ref([]);

			onMounted(() => {
				getCategories();
			});

			const getCategories = async () => {
				try {
					const {
						data: { categories },
					} = await axios.get("/categories/get-all");
					categories_data.value = categories;
					load_category.value = true;
				} catch (error) {
					await handlerErrors(error);
				}
			};

			const closeModal = () => instance.parent.ctx.closeModal();

			const schema = computed(() => {
				return yup.object({
					name: yup.string().required(),
					price: yup.number().required().positive(),
					stock: yup.number().required().min(0).integer(),
					description: yup.string().required(),
					category: yup.string().required(),
				});
			});

			const saveProduct = async () => {
				try {
					const data = createFormData(product.value);
					if (is_create.value) {
						// console.log(data);

						await axios.post("/products", data);
					} else {
						//get porque enviamos un FormData
						await axios.post(
							`/products/update/${product.value.id}`,
							data
						);
					}

					successMessage({ is_delete: false, reload: false }).then(() =>
						successRespose()
					);
				} catch (error) {
					back_errors.value = await handlerErrors(error);
				}
			};

			const previewImage = (event) => {
				file.value = event.target.files[0];
				image_preview.value = URL.createObjectURL(file.value);
			};

			const createFormData = (data) => {
				const form_data = new FormData();

				if (file.value)
					form_data.append("file", file.value, file.value.name);
				for (const prop in data) {
					form_data.append(prop, data[prop]);
				}
				return form_data;
			};

			const successRespose = () => {
				instance.parent.ctx.reloadState();
				closeModal();
			};

			return {
				product,
				is_create,
				back_errors,
				closeModal,
				saveProduct,
				image_preview,
				previewImage,
				schema,
				load_category,
				categories_data,
			};
		},
	};
</script>

<style>
</style>
