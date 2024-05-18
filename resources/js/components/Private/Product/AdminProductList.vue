<template>
	<div class="card m-5">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-success" @click="createProduct">Add product</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table " id="product_table">
					<thead>
						<tr>
							<th>Image</th>
							<th>Name</th>
							<th>Price</th>
							<th>Stock</th>
							<th>Description</th>
							<th>Category</th>
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
		<product-modal :product_data="product" />
	</div>
</template>

<script>
	import { ref, onMounted } from "vue";
	import ProductModal from "./ProductModal.vue";
	import handlerModal from "@/helpers/HandlerModal.js";
	import {
		successMessage,
		handlerErrors,
		deleteMessage,
	} from "@/helpers/Alerts.js";

	export default {
		components: { ProductModal },
		setup() {
			const table = ref(null);
			const product = ref(null);
			const { openModal, closeModal, load_modal } = handlerModal();
			onMounted(() => index());

			const index = () => {
				mounteTable();
			};

			const createProduct = async () => {
				product.value = null;
				await openModal("product_modal");
			};

			const handleAction = (event) => {
				const button = event.target;
				const product_id = button.getAttribute("data-id");

				if (button.getAttribute("role") == "edit") {
					editProduct(product_id);
				} else if (button.getAttribute("role") == "delete") {
					deleteProduct(product_id);
				}
			};

			const editProduct = async (id) => {
				try {
					const { data } = await axios.get(`/products/${id}`);
					product.value = data.product;
					await openModal("product_modal");
				} catch (error) {
					await handlerErrors(error);
				}
			};

			const deleteProduct = async (id) => {
				if (!(await deleteMessage())) return;
				try {
					await axios.delete(`/products/${id}`);
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

			const mounteTable = () => {
				table.value = $("#product_table").DataTable({
					destroy: true,
					processing: true,
					serverSide: true,
					scrollX: true,
					order: [[0, "asc"]],
					autoWidth: false,
					dom: "Bfrtip",
					buttons: ["pageLength", "excel", "pdf", "copy"],
					ajax: `/products`,
					columns: [
						{
							name: "image",
							orderable: false,
							searchable: false,
							render: (data, type, row, meta) => {
								return `<img src="${row.file.route}" alt="Product Image" style="display:block; margin: 0 auto;width: 100px; height: 100px; object-fit:cover; object-position:top; border-radius: 5px;">`;
							},
						},
						{
							data: "format_name",
							name: "format_name",
							orderable: true,
							searchable: true,
						},
						{
							data: "price",
							name: "price",
							orderable: true,
							searchable: true,
						},
						{
							data: "stock",
							name: "stock",
							orderable: true,
							searchable: true,
						},
						{
							data: "format_description",
							name: "format_description",
							orderable: true,
							searchable: true,
						},
						{
							data: "category.name",
							name: "category.name",
							orderable: true,
							searchable: true,
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

			return {
				product,
				createProduct,
				closeModal,
				load_modal,
				reloadState,
				handleAction,
			};
		},
	};
</script>
