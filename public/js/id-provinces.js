const PROVINCE_URL = `http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`
const REGENCY_URL = `http://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`
const DISTRICT_URL = `https://api.rajaongkir.com/starter/city`
const VILLAGE_URL = `https://api.rajaongkir.com/starter/city`

let provinces = () => {
    fetch(PROVINCE_URL)
        .then(response => response.json())
        .then(provinces => console.log(provinces))
}

let regencies = (provinceId) => {
    fetch(REGENCY_URL(provinceId))
        .then(response => response.json())
        .then(regencies => console.log(regencies))
}

let districts = (regencyId) => {
    fetch(`http://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
        .then(response => response.json())
        .then(districts => console.log(districts))
}

let villages = (districtId) => {
    fetch(`http://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`)
        .then(response => response.json())
        .then(villages => console.log(villages))
}