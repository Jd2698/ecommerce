<template>
	<div v-for="(category, index) in categories" :key="index">
		<a :href="'/categories/' + category.id">{{category.name}}</a>
	</div>
</template>

<script>
	import { ref, onMounted } from "vue";
	export default {
		// props: [],
		setup(/* props */) {
			const categories = ref(null);

			const getCategories = () => {
				axios
					.get(`/categories/home`)
					.then((res) => {
						categories.value = res.data.categories;
					})
					.catch((error) => {
						console.log(error);
					});
			};

			onMounted(() => getCategories());
			return { categories, getCategories };
		},
	};
</script>
