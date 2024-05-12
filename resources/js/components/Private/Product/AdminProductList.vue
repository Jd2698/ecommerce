<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-success">Add product</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="product_table">
					<thead>
						<tr>
							<th>Image</th>
							<th>Name</th>
							<th>Price</th>
							<th>Stock</th>
							<th>Description</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>

<script>
	import { ref, onMounted } from "vue";
	export default {
		// props: [],
		setup() {
			const table = ref(null);

			onMounted(() => mounteTable());

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
								return `<img src="${row.file.route}" alt="Product Image" style="max-width: 100px;">`;
							},
						},
						{
							data: "name",
							name: "name",
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

			return {};
		},
	};
</script>
