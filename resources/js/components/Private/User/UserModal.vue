<template>
	<div class="modal fade" id="user_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{is_create ? 'crear':'editar'}} user</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
				</div>
				<Form @submit="saveUser" :validation-schema="schema" ref="form">
					<div class="modal-body">
						<section class="row">

							<!-- Show Image -->
							<div class="col-12 d-flex justify-content-center mt-1">
								<img :src="image_preview" alt="Imagen User" class="img-thumbnail" width="170" height="170">
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
								<Field name="name" v-slot="{ errorMessage, field }" v-model="user.name">
									<input type="text" id="name" v-model="user.name" :class="`form-control ${errorMessage || back_errors['name'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['name'] }}</span>
								</Field>
							</div>

							<!-- last name -->
							<div class="col-12">
								<label for="last_name">Last name</label>
								<Field name="last_name" v-slot="{ errorMessage, field }" v-model="user.last_name">
									<input type="text" id="last_name" v-model="user.last_name" :class="`form-control ${errorMessage || back_errors['last_name'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['last_name'] }}</span>
								</Field>
							</div>

							<!-- email -->
							<div class="col-12">
								<label for="email">Email</label>
								<Field name="email" v-slot="{ errorMessage, field }" v-model="user.email">
									<input type="text" id="email" v-model="user.email" :class="`form-control ${errorMessage || back_errors['email'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['email'] }}</span>
								</Field>
							</div>

							<!-- contraseña -->
							<div class="col-12">
								<label for="password">Contraseña</label>
								<Field name="password" v-slot="{ errorMessage, field }" v-model="user.password">
									<input type="password" id="password" v-model="user.password" :class="`form-control ${errorMessage || back_errors['password'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['password'] }}</span>
								</Field>

							</div>
							<!-- password confirmation -->
							<div class="col-12">
								<label for="password_confirmation">Confirmar contraseña</label>
								<Field name="password_confirmation" v-slot="{ errorMessage, field }" v-model="user.password_confirmation">
									<input type="password" id="password_confirmation" v-model="user.password_confirmation" :class="`form-control ${errorMessage || back_errors['password_confirmation'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{ back_errors['password_confirmation'] }}</span>
								</Field>

							</div>

							<!-- Roles -->
							<div class="col-12 mt-2" v-if="load_role">
								<Field name="role" v-slot="{ errorMessage, field, valid }" v-model="user.role">
									<label for="role">Role</label>

									<v-select id="role" :options="roles_data" v-model="user.role" :reduce="role => role.name" v-bind="field" label="name" placeholder="Selecciona" :clearable="false" :class="`${errorMessage || back_errors['category'] ? 'is-invalid' : ''}`">
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
		props: ["user_data"],
		components: { Field, Form },
		setup(props) {
			const instance = getCurrentInstance();

			const load_role = ref(false);
			const roles_data = ref([]);

			const is_create = props.user_data ? ref(false) : ref(true);
			const user = is_create.value ? ref({}) : ref(props.user_data);

			const image_preview = is_create.value
				? ref("")
				: ref(user.value.file.route);

			const file = ref(null);
			const back_errors = ref({});

			onMounted(() => {
				getRoles();
				if (!is_create.value) {
					user.value.role = user.value.roles[0].name;
				}
			});

			const getRoles = async () => {
				try {
					const {
						data: { categories },
					} = await axios.get("/users/getRoles");
					roles_data.value = categories;
					load_role.value = true;
				} catch (error) {
					await handlerErrors(error);
				}
			};

			const closeModal = () => instance.parent.ctx.closeModal();

			const schema = computed(() => {
				let passwordYup = yup.string();

				is_create.value
					? passwordYup.email().required().min(8)
					: passwordYup.notRequired();

				return yup.object({
					name: yup.string().required(),
					last_name: yup
						.string()
						.required("Last name is a required field"),
					email: yup.string().email().required(),
					password: passwordYup,
					password_confirmation: yup
						.string()
						.oneOf([yup.ref("password"), null], "Passwords must match"),
					role: yup.string().required(),
				});
			});

			const saveUser = async () => {
				try {
					const data = createFormData(user.value);
					if (is_create.value) {
						await axios.post("/users", data);
					} else {
						// get porque enviamos un FormData
						await axios.post(`/users/update/${user.value.id}`, data);
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
				user,
				is_create,
				back_errors,
				closeModal,
				saveUser,
				image_preview,
				previewImage,
				schema,
				load_role,
				roles_data,
			};
		},
	};
</script>

<style>
</style>
