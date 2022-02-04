--- плохо написанный код 

$address = 'One Infinite Loop, Cupertino 95014';
$cityZipCodeRegex = '/^[^,\\]+[,\\\s]+(.+?)\s*(\d{5})?$/';
preg_match($cityZipCodeRegex, $address, $matches);

saveCityZipCode($matches[1], $matches[2]);

--- хорошо написанный код 

$address = 'One Infinite Loop, Cupertino 95014';
$cityZipCodeRegex = '/^[^,\\]+[,\\\s]+(.+?)\s*(\d{5})?$/';
preg_match($cityZipCodeRegex, $address, $matches);

list(, $city, $zipCode) = $matches;
saveCityZipCode($city, $zipCode);

--- плохо написанный код 

$l = ['Austin', 'New York', 'San Francisco'];

for ($i = 0; $i < count($l); $i++) {
    $li = $l[$i];
    doStuff();
    doSomeOtherStuff();
    // ...
    // ...
    // ...
    // Wait, what is `$li` for again?
    dispatch($li);
}

--- хорошо написанный код 

$locations = ['Austin', 'New York', 'San Francisco'];

foreach ($locations as $location) {
    doStuff();
    doSomeOtherStuff();
    // ...
    // ...
    // ...
    dispatch($location);
});

--- плохо написанный код 

$car = [
    'carMake'  => 'Honda',
    'carModel' => 'Accord',
    'carColor' => 'Blue',
];

function paintCar(&$car) {
    $car['carColor'] = 'Red';
}

--- хорошо написанный код 

$car = [
    'make'  => 'Honda',
    'model' => 'Accord',
    'color' => 'Blue',
];

function paintCar(&$car) {
    $car['color'] = 'Red';
}