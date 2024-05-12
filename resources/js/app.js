import './bootstrap'
import { createApp } from 'vue'
import vSelect from 'vue-select'

// Components ---------------------------------------------------
import TheCategoryList from './components/Public/Category/TheCategoryList.vue'
import TheProductList from './components/Public/Product/TheProductList.vue'

const app = createApp({
	components: {
		TheCategoryList,
		TheProductList
	}
})

app.component('v-select', vSelect)
app.mount('#app')
