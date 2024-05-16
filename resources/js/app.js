import './bootstrap'
import { createApp } from 'vue'
import vSelect from 'vue-select'

// Components ---------------------------------------------------
import TheCategoryList from './components/Public/Category/TheCategoryList.vue'
import TheProductList from './components/Public/Product/TheProductList.vue'
import ShopProduct from './components/Public/Product/ShopProduct.vue'

import AdminProductList from './components/Private/Product/AdminProductList.vue'
import AdminCategoryList from './components/Private/Category/AdminCategoryList.vue'
import AdminUserList from './components/Private/User/AdminUserList.vue'

const app = createApp({
	components: {
		TheCategoryList,
		TheProductList,
		ShopProduct,
		AdminProductList,
		AdminCategoryList,
		AdminUserList
	}
})

app.component('v-select', vSelect)
app.mount('#app')
