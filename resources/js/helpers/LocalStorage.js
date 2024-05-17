export const getObjects = id => {
	let data = []
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (!clave.includes('product-invoiceTotal') && clave.startsWith(`${id}-`)) {
			let itemData = localStorage.getItem(clave)

			let obj = JSON.parse(itemData)

			data.push(obj)
		}
	}
	return data
}

export const deleteObject = objData => {
	// userId-productId
	console.log(objData)
	// localStorage.removeItem()
}

export const addObject = (objProduct, objData) => {
	let keyName = `${objData.user}-${objData.product}`
	localStorage.setItem(keyName, JSON.stringify(objProduct))
}

export const getObject = index => {
	let item = JSON.parse(localStorage.getItem(index))
	return item
}

export const addTotal = () => {
	let total = 0
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (!clave.includes('product-invoiceTotal')) {
			let clave = localStorage.key(i)
			let itemData = localStorage.getItem(clave)

			let obj = JSON.parse(itemData)

			total += obj.subtotal
		}
	}
	addObject(total, 'invoiceTotal')
	return total
}
