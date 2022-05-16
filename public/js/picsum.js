let setCookie = (name, value, miliseconds = 60000) => {
    var date = new Date()
    date.setTime(date.getTime() + miliseconds)
    var expires = "expires=" + date.toUTCString()
    document.cookie = name + "=" + value + ";" + expires + ";path=/"
}

let getCookie = (name) => {
    let cookieName = name + "="
    let decodedCookie = decodeURIComponent(document.cookie)
    let cookieArray = decodedCookie.split(';')
    for(let i = 0; i < cookieArray.length; i++) {
        let c = cookieArray[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1)
        }
        if (c.indexOf(cookieName) == 0) {
            return c.substring(cookieName.length, c.length)
        }
    }
    return ""
}

let checkCookie = (cookieName) => {
    let cookie = getCookie(cookieName)
    return cookie != "" ? true : false
}

let getRandomPicture = () => {
    let url = "https://picsum.photos/1920/1080/"
    if( ! checkCookie('picsum')) {
        fetch(url).then(response => {
            setCookie('picsum', response.url)
        })
    }
    let picsum = getCookie('picsum')
    setBackgroundPicture(picsum)
}

let setBackgroundPicture = (url) => {
    const authRight = $('#auth-right')

    authRight.css({
        "background-image": `url('${url}')`,
        "background-position": "cover"
    })
}

getRandomPicture()