const weatherIcons = {
    "Rain": "wi wi-day-rain",
    "Clouds": "wi wi-day-cloudy",
    "Clear": "wi wi-day-sunny",
    "Snow": "wi wi-day-snow",
    "Mist": "wi wi-day-fog",
    "Drizzle": "wi wi-day-sleet",
    "NightRain": "wi wi-night-rain",
    "NightClouds": "wi wi-night-cloudy",
    "NightClear": "wi wi-night-sunny",
    "NightSnow": "wi wi-night-snow",
    "NightMist": "wi wi-night-fog",
    "NightDrizzle": "wi wi-night-sleet"
}

function capitalize(str){
    return str[0].toUpperCase() + str.slice(1);
}

async function main() {

    //Recup l'adresse IP du PC qui ouvre la page
    //fetch est async

    const ip = await fetch('https://api.ipify.org?format=json')
        .then(resultat => resultat.json())
        .then(json => json.ip);

    //Recup la longitude grace à l'adresse IP
    const location = await fetch('https://freegeoip.live/json/' + ip, {
        headers: {
            'Access-Control-Request-Headers': '*'
        }
      })
        .then(resultat => resultat.json());
    const long = location.longitude;
    const lat = location.latitude;

    /*
    //Recup la latitude grace à l'adresse IP
    const lat = await fetch('https://freegeoip.live/json/' + ip, {
        headers: {
            'Access-Control-Request-Headers': '*'
        }
      })
        .then(resultat => resultat.json())
        .then(json => json.latitude);
        console.log(lat);

        */
    //Recup les infos de la meteo grace à la ville            
    const meteo = await fetch('http://api.openweathermap.org/data/2.5/weather?lat=' + lat + '&lon=' + long + '&appid=162e6021fefe1ceb124ffcebc9bf205d&lang=fr&units=metric')
        .then(resultat => resultat.json())
        .then(json => json);
    
    console.log(meteo)
    //Afficher les informations
    displayWeatherInfos(meteo);
    
}

function displayWeatherInfos(data) {
    const name = data.name;
    const temperature = data.main.temp;
    const conditions = data.weather[0].main;
    const description = data.weather[0].description;

    document.querySelector('#ville').textContent = name;
    document.querySelector('#temperature').textContent = Math.round(temperature);
    document.querySelector('#conditions').textContent = capitalize(description);
    document.querySelector('.wi').className = weatherIcons[conditions];

    setTimeout(main, 18000000);
}

main();


