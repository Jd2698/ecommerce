<template>
	<div class="modal fade" id="user_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{is_create ? 'crear':'editar'}} category</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
				</div>

				<backend-error :errors="back_errors" />

				<Form @submit="saveCategory" :validation-schema="schema">
					<div class="modal-body">
						<div class="col-12">
							<label for="name">Nombre</label>
							<Field name="name" v-model="category.name" v-slot="{errorMessage, field}">
								<input :class="`form-control ${errorMessage || back_errors['name'] ? 'is-invalid' : ''}`" id="name" v-bind="field">
								<span class="invalid-feedback">{{errorMessage}}</span>
								<span class="invalid-feedback">{{back_errors['name']}}</span>
							</Field>
						</div>
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
	import { ref, getCurrentInstance, computed } from "vue";
	import { Field, Form } from "vee-validate";
	import * as yup from "yup";

	export default {
		props: ["category_data"],
		components: { Field, Form },
		setup({ category_data }) {
			const instance = getCurrentInstance();
			const is_create = category_data ? ref(false) : ref(true);
			const category = !is_create.value ? ref(category_data) : ref({});
			const back_errors = ref({});
			const closeModal = () => instance.parent.ctx.closeModal();

			const saveCategory = async () => {
				try {
					if (is_create.value) {
						await axios.post("/categories", category.value);
					} else {
						await axios.put(
							`/categories/${category.value.id}`,
							category.value
						);
					}
					successMessage({ is_delete: false, reload: false }).then(() =>
						successRespose()
					);
				} catch (error) {
					back_errors.value = await handlerErrors(error);
				}
			};

			const successRespose = () => {
				instance.parent.ctx.reloadState();
				closeModal();
			};

			const schema = computed(() => {
				return yup.object({
					name: yup.string().required(),
				});
			});

			return {
				category,
				is_create,
				schema,
				back_errors,
				closeModal,
				saveCategory,
			};
		},
	};
</script>

<style>
</style>
