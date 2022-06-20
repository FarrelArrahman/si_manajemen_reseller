const PROVINCE_URL = `https://api.rajaongkir.com/starter/province`
const CITY_URL = `https://api.rajaongkir.com/starter/city`
const COST_URL = `https://api.rajaongkir.com/starter/cost`
const API_KEY = `e22f1c6f62ab0ff49b35f91cf61a3362`

let param = {
    key: API_KEY
}

let provinces = () => {
    return fetch(PROVINCE_URL + '?' + new URLSearchParams(param))
        .then(response => response.json())
}

let cities = (provinceId) => {
    if(provinceId != null) {
        param.province = provinceId
    }

    return fetch(CITY_URL + '?' + new URLSearchParams(param))
        .then(response => response.json())
}

let costs = (cityId) => {
    fetch(COST_URL).then(response => response.json())
}