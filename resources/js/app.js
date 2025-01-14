import StoreLocator from './store-locator';

const storeLocators = document.querySelectorAll('.otomaties-store-locator');
if (storeLocators.length) {
	storeLocators.forEach(map => {
		new StoreLocator(map);
	});
}
