/* global google:true */

export default class StoreLocator {
    constructor(el)
    {
        this.el = el;
        this.markers = [];
        this.mapEl = this.el.querySelector('.otomaties-store-locator__map');
        this.filtersEl = this.el.querySelector('.otomaties-store-locator__filters');
        this.openWindow = null;
        this.options = {
            zoom: parseInt(el.getAttribute('data-zoom')),
        }
        this.renderMap();
        this.bindEvents();
    }

    getMarkersFromHtml()
    {
        let markers = [];
        const markerElements = this.mapEl.querySelectorAll('.store');
        Array.from(markerElements).forEach(markerElement => {
            const lat = markerElement.getAttribute('data-lat');
            const lng = markerElement.getAttribute('data-lng');
            markers.push({
                'latLng': new google.maps.LatLng(lat, lng),
                'title' : markerElement.getAttribute('data-title'),
                'content': markerElement.innerHTML,
                'icon': markerElement.getAttribute('data-marker'),
                'categories': markerElement.getAttribute('data-categories').split(',').map(Number),
            });
        });
        return markers;
    }
    
    renderMap()
    {
        const markers = this.getMarkersFromHtml();
        
        this.map = new google.maps.Map(this.mapEl, {
            zoom: this.options.zoom,
            center: new google.maps.LatLng(50.85045, 4.34878),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
        
        let storeLocator = this;
        
        for (let i = 0; i < markers.length; i++) {
            let marker = new google.maps.Marker({
                position: markers[i].latLng,
                icon: markers[i].icon ? markers[i].icon : null,
                map: this.map,
                categories: markers[i].categories,
            });
            
            const infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    if (storeLocator.openWindow) {
                        storeLocator.openWindow.close();
                    }
                    infowindow.setContent(markers[i].content);
                    infowindow.setOptions({minWidth: 250, maxWidth: 350});
                    infowindow.open(this.map, marker);
                    storeLocator.openWindow = infowindow;
                }
            })(marker, i));
            
            this.markers.push(marker);
        }
        this.centerMap();
    }
    
    centerMap()
    {
        let markers = this.markers.filter(marker => marker.map !== null);
        
        if (!markers.length) {
            markers = this.markers;
        }
        
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            const latLng = markers[i].position;
            bounds.extend(latLng);
        }
        
        if (false || markers.length > 1) {
            this.map.fitBounds(bounds);
        } else {
            this.map.setCenter(bounds.getCenter());
        }
    }
    
    bindEvents()
    {
        this.filtersEl.addEventListener('change', () => {
            const checkboxes = this.filtersEl.querySelectorAll('input[type="checkbox"]');
            const categories = Array.from(checkboxes).filter(checkbox => checkbox.checked).map(checkbox => parseInt(checkbox.value));
            this.filter(categories);
        });
    }
    
    filter(categories)
    {
        for (let i = 0; i < this.markers.length; i++) {
            if (!categories.length || this.markers[i].categories.filter(value => categories.includes(value)).length) {
                this.markers[i].setMap(this.map);
            } else {
                this.markers[i].setMap(null);
            }
        }
        
        this.centerMap();
    }
}
