function initCompareModule(){	
	getCompareStorage();
	addCompareButton();
	changeBadgeCount();
	updateLink();
}

function getCompareStorage(){
	let compareStorage = localStorage.getItem('compareStorage');	
	if (compareStorage === null) {
		compareStorage = [];
	} else {
		compareStorage = compareStorage.split(',');
	}
	return compareStorage;
}

function addCompareButton() {
	let add_to_cart_buttons = document.getElementsByClassName('addtocart-area');
	let compareStorage = getCompareStorage();
	for (let elem of add_to_cart_buttons) {
		let product_id = elem.querySelector('[name="pid"]').getAttribute("value");			
		let product_to_compare_link = '<input type="checkbox" id="compareCheckbox_' + product_id + '"  onclick="checkProductCompareList(' + product_id + ')"><span id="product_to_compare_' + product_id + '" onclick="setTimeout(clickCompareCheckbox(' + product_id + '), 10)"> Сравнить</span>';
		elem.insertAdjacentHTML('afterend', '<div class="row"><div class="compare_badge" id="compare_badge">' + product_to_compare_link + '</div></div>');
		checkbox = document.getElementById('compareCheckbox_' + product_id);
		let idx = compareStorage.indexOf(product_id.toString());
		if ( idx !== -1){
			checkbox.checked = true;
		} 
	}	
}

function checkProductCompareList(product_id){	
	let compareStorage = getCompareStorage();
	let idx = compareStorage.indexOf(product_id.toString());

	if ( idx == -1){
		compareStorage.push(product_id);
		compareStorage.sort();
		localStorage.setItem('compareStorage', compareStorage);
	} else {
		if (compareStorage.length == 1){
			localStorage.removeItem('compareStorage');
		} else {
			compareStorage = compareStorage.filter(element => element !== product_id.toString());
			compareStorage.sort();
			localStorage.setItem('compareStorage', compareStorage);
		}	
	}
	updateLink();
	changeBadgeCount();
}

function removeProductCompareList(product_id){	
	let compareStorage = getCompareStorage();
	let idx = compareStorage.indexOf(product_id.toString());

	if ( idx == -1){
		compareStorage.push(product_id);
		compareStorage.sort();
		localStorage.setItem('compareStorage', compareStorage);
	} else {
		if (compareStorage.length == 1){
			localStorage.removeItem('compareStorage');
		} else {
			compareStorage = compareStorage.filter(element => element !== product_id.toString());
			compareStorage.sort();
			localStorage.setItem('compareStorage', compareStorage);
		}	
	}
	url = updateLink();
	changeBadgeCount();
	// location.href = url;
}

function removeProductFromComparePage(product_id){
	removeProductCompareList(product_id);	
	let elememls = document.getElementsByClassName('compare_item_' + product_id);
	//elememls.remove();
	for (let elem of elememls) {
		//elem.remove();
		elem.style.display = 'none';
	}
}

function updateLink(){
	let compareStorage = getCompareStorage();
	let url = 'index.php?option=com_vmcompare&products=';
	let compareLink = document.getElementById('compare_link');

	if (compareStorage.length>0){
		// console.log(compareStorage);
		for (let id of compareStorage){
			url = url + id + ',';
		}
		url = url.slice(0,-1);
		compareLink.href = url;
		return url;
	} else {
		compareLink.href = 'index.php?option=com_vmcompare';
		return 'index.php?option=com_vmcompare';
	}	
}

function changeBadgeCount(){
	let compareStorage = getCompareStorage();
	let compareCountBadge = document.getElementById('total_compare_product');
	compareCountBadge.innerText = compareStorage.length;
}

function clickCompareCheckbox(id){
	document.getElementById('compareCheckbox_' + id).click();
}

document.addEventListener("DOMContentLoaded", initCompareModule);

// public static function getCompareList(){
	// 	$compareList = new ArrayObject();
	// 	$compareUrl = explode(',', trim($_GET['products']));
	// 	foreach ($compareUrl as $key => $value) {
	// 		if (is_numeric($value)){
	// 			$compareList->append($value);
	// 		}
	// 	}		
	// 	//$compareList = is_test();
	// 	return $compareList;		
	// }