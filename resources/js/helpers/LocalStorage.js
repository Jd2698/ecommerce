export const getProducts = user_id => {
	if (localStorage.getItem(user_id)) {
		let itemData = localStorage.getItem(user_id)

		let obj = JSON.parse(itemData)
		return obj
	}
}

export const deleteObject = key => {
	localStorage.removeItem(key)
}

export const addObject = (key, obj) => {
	localStorage.setItem(key, JSON.stringify(obj))
}

export const getObject = index => {
	let item = JSON.parse(localStorage.getItem(index))
	return item
}

export const addTotal = user_id => {
	if (localStorage.getItem(user_id)) {
		const itemData = localStorage.getItem(user_id)
		const obj = JSON.parse(itemData)

		const total = obj.reduce((cant, product) => product.subtotal + cant, 0)

		localStorage.setItem('invoiceTotal', total)
		return total
	}
}
