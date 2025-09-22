<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
  
     
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
	<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster-src.js"></script>
     
</head>
<style>
    html, body {
      height: 100%;
      margin: 0;
    }
    .leaflet-container {
      height: 600px;
      width: 800px;
      max-width: 100%;
      max-height: 100%;
    }
    #filteroptions .expander {
      width: 100%;
      height: 40px;
      position: absolute;
      z-index: 10000;
      background: black;
      color: white;
      text-align: center;
      line-height: 40px;
      cursor: pointer;
      display: block;
    }
    #filteroptions .options {
      width: 100%;
      height: 400px;
      position: absolute;
      z-index: 10000;
      display: block;
      background: gray;
    }
    #filteroptions:not(.active) .options {
      display: none;
    }
    #filteroptions.active .expander {
      display: none;
    }
  </style>
<body>

<div id="filteroptions">
  <div class="expander">Filter einblenden</div>
  <div class="options">
    <form action="">
      <label for="art">Immobilienart:</label>
      <select name="art" id="art">
        <option value="0" <?php if(isset($_GET['art']) && $_GET['art'] == "0") echo "selected"; ?>>mietwohnungen</option>
        <option value="1" <?php if(isset($_GET['art']) && $_GET['art'] == "1") echo "selected"; ?>>eigentumswohnungen</option>
        <option value="2" <?php if(isset($_GET['art']) && $_GET['art'] == "2") echo "selected"; ?>>haus-kaufen</option>
      </select>
      <br />
      <label for="district">Bezirk:</label>
      <input type="text" name="district" id="district" value="<?php echo isset($_GET['district']) ? htmlspecialchars($_GET['district']) : 'steiermark/graz'; ?>">
      <br />
      <label for="price_from">Preis von:</label>
      <input type="number" name="price_from" id="price_from" min="0" step="1" value="<?php echo isset($_GET['price_from']) ? htmlspecialchars($_GET['price_from']) : '400'; ?>">
      <br />
      <label for="price_to">Preis bis:</label>
      <input type="number" name="price_to" id="price_to" min="0" step="1" value="<?php echo isset($_GET['price_to']) ? htmlspecialchars($_GET['price_to']) : '1200'; ?>">
      <br />
      <label for="area_from">Fläche von (m²):</label>
      <input type="number" name="area_from" id="area_from" min="0" step="1" value="<?php echo isset($_GET['area_from']) ? htmlspecialchars($_GET['area_from']) : '65'; ?>">
      <br />
      <label for="area_to">Fläche bis (m²):</label>
      <input type="number" name="area_to" id="area_to" min="0" step="1" value="<?php echo isset($_GET['area_to']) ? htmlspecialchars($_GET['area_to']) : '80'; ?>">
      <br />
      <label for="pages">Seiten:</label>
      <input type="number" name="pages" id="pages" min="1" step="1" value="<?php echo isset($_GET['pages']) ? htmlspecialchars($_GET['pages']) : '5'; ?>">
      <!--<br />
      <label for="rows">Zeilen pro Seite:</label>
      <input type="number" name="rows" id="rows" min="1" step="1" value="200">-->
      <br />
      <label for="sort">Sortierung:</label>
      <select name="sort" id="sort">
        <option value="0" <?php if(isset($_GET['sort']) && $_GET['sort'] == "0") echo "selected"; ?>>Aktualität</option>
        <!--<option value="1" <?php if(isset($_GET['sort']) && $_GET['sort'] == "1") echo "selected"; ?>>Nähe</option>-->
        <option value="2" <?php if(isset($_GET['sort']) && $_GET['sort'] == "2") echo "selected"; ?>>Miete aufsteigend</option>
        <option value="3" <?php if(isset($_GET['sort']) && $_GET['sort'] == "3") echo "selected"; ?>>Miete absteigend</option>
        <option value="4" <?php if(isset($_GET['sort']) && $_GET['sort'] == "4") echo "selected"; ?>>Fläche aufsteigend</option>
        <option value="5" <?php if(isset($_GET['sort']) && $_GET['sort'] == "5") echo "selected"; ?>>Fläche absteigend</option>
        <option value="6" <?php if(isset($_GET['sort']) && $_GET['sort'] == "6") echo "selected"; ?>>Relevanz</option>
      </select>
      <br />
      <input type="submit" value="Filtern">
      </form>
    </div>
  </div>
</div>

<script>
document.querySelector('#filteroptions .expander').addEventListener('click', function() {
  var wrapper = document.querySelector('#filteroptions');
  wrapper.classList.add('active');
});
</script>

<div id="map" style="width: 100%; height: 100%;"></div>
<script>

  const map = L.map('map').setView([47.0313,15.4105], 12);

  const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

</script>