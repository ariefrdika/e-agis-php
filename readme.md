# E-AGIS (weAther GIS)
**E-AGIS** merupakan sebuah Web GIS (_Geographic Information System_) untuk memetakan cuaca di daerah Jawa Timur. Dengan memanfaatkan API yang disediakan **BMKG** (source: https://data.bmkg.go.id/prakiraan-cuaca/) dan file .geojson kota/kabupaten di Jawa Timur (source: https://github.com/taufiqurrohmansuwarto/jatim-geojson) web ini berhasil di buat.

## Screen Capture
![screen capture E-AGIS](https://i.ibb.co/4wLk6qQ/Screenshot-2020-11-06-122316.png)

### Instalasi model data
```php
include 'model.php';
$model = new getAPI("https://data.bmkg.go.id/datamkg/MEWS/DigitalForecast/DigitalForecast-JawaTimur.xml");
```
### Fungsi getMainData()
Fungsi *getMainData()* akan mengembalikan Array dengan key setiap kota yang memiliki properti tanggal update perkiraan cuaca dan perkiraan cuaca. Dengan ketentuan 4 data terbaru yang akan ditampilkan.
```php
print_r($model ->getMainData());
```
**Output :** Array ( [Bangkalan] => Array ( [0] => Array ( [datetime] => 06 Nov 2020 (18:00) [cuaca] => Cerah ) [1] => Array ( [datetime] => 07 Nov 2020 (00:00) [cuaca] => Cerah ) [2] => Array ( [datetime] => 07 Nov 2020 (06:00) [cuaca] => Hujan Ringan ) [3] => Array ( [datetime] => 07 Nov 2020 (12:00) [cuaca] => Cerah ) ) ... )

### Fungsi cuacaSaatIni()
Fungsi *cuacaSaatIni()* hampir sama dengan fungsi sebelumnya tetapi hanya mengembalikan data perkiraan cuaca saat ini. 
```php
print_r($model ->cuacaSaatIni());
```
**Output :** Array ( [Bangkalan] => Array ( [0] => Array ( [datetime] => 06 Nov 2020 (12:00) [cuaca] => Cerah [warna] => #FAC900 ) ) ... )
