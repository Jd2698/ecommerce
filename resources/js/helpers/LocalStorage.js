export const getObjects = id => {
	let data = []
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (!clave.includes('invoiceTotal') && clave.startsWith(`${id}-`)) {
			let itemData = localStorage.getItem(clave)

			let obj = JSON.parse(itemData)

			data.push(obj)
		}
	}
	return data
}

export const deleteObject = objData => {
	console.log(objData)
	localStorage.removeItem(`${objData.user}-${objData.product}`)
}

export const addObject = (obj, objData) => {
	let keyName
	if ('invoiceTotal' in objData) {
		keyName = `${objData.user}-${objData.invoiceTotal}`
	} else {
		keyName = `${objData.user}-${objData.product}`
	}

	localStorage.setItem(keyName, JSON.stringify(obj))
}

export const getObject = index => {
	let item = JSON.parse(localStorage.getItem(index))
	return item
}

export const addTotal = objData => {
	let total = 0
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (
			!clave.includes('invoiceTotal') &&
			clave.startsWith(`${objData.user.id}-`)
		) {
			let clave = localStorage.key(i)
			let itemData = localStorage.getItem(clave)

			let obj = JSON.parse(itemData)

			total += obj.subtotal
		}
	}
	addObject(total, { invoiceTotal: 'invoiceTotal', user: objData.user.id })
	return total
}

export const deleteObjects = id => {
	for (let i = 0; i < localStorage.length; i++) {
		let clave = localStorage.key(i)
		if (clave.startsWith(`${id}-`)) {
			localStorage.removeItem(clave)
		}
	}
}
