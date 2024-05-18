export const getProductsObject = user_id => {
	let data = []
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (!clave.includes('invoiceTotal') && clave.startsWith(`${user_id}-`)) {
			let itemData = localStorage.getItem(clave)

			let obj = JSON.parse(itemData)

			data.push(obj)
		}
	}
	return data
}

export const deleteObject = key => {
	localStorage.removeItem(key)
}

export const addObject = (key, obj) => {
	localStorage.setItem(key, JSON.stringify(obj))
}

const addTotalObject = (key, value) => {
	localStorage.setItem(key, value)
}

export const getObject = index => {
	let item = JSON.parse(localStorage.getItem(index))
	return item
}

export const addTotal = user_id => {
	let total = 0
	for (let i = 0; i < localStorage.length; i++) {
		const clave = localStorage.key(i)
		if (!clave.includes('invoiceTotal') && clave.startsWith(`${user_id}-`)) {
			let itemData = localStorage.getItem(clave)
			let obj = JSON.parse(itemData)

			total += obj.subtotal
		}
	}
	let key = `${user_id}-invoiceTotal`
	addTotalObject(key, total)
	return total
}

export const deleteObjects = user_id => {
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (clave.startsWith(`${user_id}-`)) {
			localStorage.removeItem(clave)
		}
	}
}
