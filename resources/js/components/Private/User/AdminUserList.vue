<template>
	<div class="card m-5">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-success" @click="createUser">Crear Usuario</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="user_table">
					<thead>
						<tr>
							<th>Image</th>
							<th>Nombre</th>
							<th>Email</th>
							<th>Role</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody @click="handleAction">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div v-if="load_modal">
		<user-modal :user_data="user" />
	</div>
</template>

<script>
	import { ref, onMounted } from "vue";
	import UserModal from "./UserModal.vue";
	import handlerModal from "@/helpers/HandlerModal.js";
	import {
		successMessage,
		handlerErrors,
		deleteMessage,
	} from "@/helpers/Alerts.js";

	export default {
		components: { UserModal },
		props: [],
		setup() {
			const user = ref(null);
			const table = ref(null);
			const { openModal, closeModal, load_modal } = handlerModal();

			onMounted(() => index());
			const index = () => mounteTable();

			const mounteTable = () => {
				table.value = $("#user_table").DataTable({
					destroy: true,
					processing: true,
					serverSide: true,
					scrollX: true,
					order: [[0, "asc"]],
					autoWidth: false,
					dom: "Bfrtip",
					buttons: ["pageLength", "excel", "pdf", "copy"],
					ajax: `/users/get-all`,
					columns: [
						{
							name: "image",
							orderable: false,
							searchable: false,
							render: (data, type, row, meta) => {
								return `<img src="${row.file.route}" alt="Product Image" style="width: 100px;">`;
							},
						},
						{
							data: "name",
							name: "name",
							orderable: true,
							searchable: true,
						},
						{
							data: "email",
							name: "email",
							orderable: true,
							searchable: true,
						},
						{
							data: "roles",
							name: "roles",
							orderable: true,
							searchable: true,
							render: (data, type, row, meta) => {
								if (data.length > 0) {
									let roleName = data[0].name;
									return roleName;
								} else {
									return "Sin roles";
								}
							},
						},
						{
							name: "action",
							orderable: false,
							searchable: false,
							render: (data, type, row, meta) => {
								return `<div class="d-flex justify-content-center" data-role='actions'>
																																																																																																																																													            <button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="btn btn-warning btn-sm">
																																																																																																																																													              <i class='fas fa-pencil-alt' data-id='${row.id}' role='edit'></i>
																																																																																																																																																			</button>
																																																																																																																																													            <button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="btn btn-danger btn-sm ms-1">
																																																																																																																																													            	<i class='fas fa-trash-alt' data-id='${row.id}' role='delete'></i>
																																																																																																																																																			</button>
																																																																																																																																													          </div>`;
							},
						},
					],
				});
			};

			const handleAction = (event) => {
				const button = event.target;
				const user_id = button.getAttribute("data-id");

				if (button.getAttribute("role") == "edit") {
					editUser(user_id);
				} else if (button.getAttribute("role") == "delete") {
					deleteUser(user_id);
				}
			};

			const createUser = async () => {
				user.value = null;
				await openModal("user_modal");
			};

			const editUser = async (id) => {
				try {
					const { data } = await axios.get(`/users/${id}`);
					user.value = data.user_data;
					await openModal("user_modal");
				} catch (error) {
					await handlerErrors(error);
				}
			};

			const deleteUser = async (id) => {
				if (!(await deleteMessage())) return;
				try {
					await axios.delete(`/users/${id}`);
					await successMessage({ is_delete: true });
					reloadState();
				} catch (error) {
					await handlerErrors(error);
				}
			};
			const reloadState = () => {
				table.value.destroy();
				index();
			};
			return {
				user,
				load_modal,
				editUser,
				deleteUser,
				createUser,
				closeModal,
				reloadState,
				handleAction,
			};
		},
	};
</script>
