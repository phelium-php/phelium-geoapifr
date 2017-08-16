# GeoApiFr

Wrapper PHP de l'[API Geo](https://api.gouv.fr/api/api-geo.html) fournie par le gouvernement français.


## Installation

Avec Composer, ajoutez cette ligne dans votre fichier `composer.json` :

    "phelium/geoapifr": "dev-master"

Lancez ensuite `composer update`.

Ou, exécutez simplement cette commande dans votre terminal :

    composer require phelium/geoapifr


## Utilisation

```php
require 'vendor/autoload.php';

use Phelium\Component\GeoApiFr;
```


### Communes

Pour avoir les détails d'une commune, utilisez la méthode `communes()`.

Les champs disponibles en retour sont :

| Nom du champ | Type | Descriptif |
| --- | --- | --- |
| code | string | Code INSEE de la commune |
| codeDepartement | string | Code du département associé à la commune |
| codeRegion | string | Code de la région associée à la commune |
| nom | string | Nom de la commune |
| codesPostaux | array | Liste des codes postaux associés à la commune |
| surface | number | Surface de la commune, en mètres-carrés |
| population | integer | Population municipale |
| centre | array | Centre de la commune (point GeoJSON) |
| contour | array | Contour de la commune (polygon GeoJSON) |
| departement | array | Département |
| region | array | Région |

Les champs autorisés pour la recherche sont :

| Nom du champ |
| --- |
| codePostal |
| codeDepartement |
| codeRegion |
| nom |
| lon |
| lat |

Exemple de recherche de la commune dont le nom est "Versailles" :

```php
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->communes()
    ->fields(array('code', 'codeDepartement', 'codeRegion', 'nom'))
    ->search('nom', 'Versailles');
```

Retour :

    Array
    (
        [status_code] => 200
        [status_msg] => OK
        [url] => https://geo.api.gouv.fr/communes?nom=Versailles&fields=code,codeDepartement,codeRegion,nom
        [datas] => Array
            (
                [0] => Array
                    (
                        [code] => 78646
                        [codeDepartement] => 78
                        [codeRegion] => 11
                        [nom] => Versailles
                        [_score] => 1
                    )

            )
    )


### Départements

Pour avoir les détails d'un département, utilisez la méthode `departements()`.

Les champs disponibles en retour sont :

| Nom du champ | Type | Descriptif |
| --- | --- | --- |
| code | string | Code du département |
| nom | string | Nom du département |
| codeRegion | string | Code de la région associée au département |
| region | array | Région |

Les champs autorisés pour la recherche sont :

| Nom du champ |
| --- |
| code |
| codeRegion |
| nom |

Exemple de recherche du département dont le code est "2A" :

```php
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->departements()
    ->fields(array('code', 'codeRegion', 'nom'))
    ->search('code', '2A');
```

Retour :

    Array
    (
        [status_code] => 200
        [status_msg] => OK
        [url] => https://geo.api.gouv.fr/departements?code=2A&fields=code,codeRegion,nom
        [datas] => Array
            (
                [0] => Array
                    (
                        [code] => 2A
                        [codeRegion] => 94
                        [nom] => Corse-du-Sud
                    )

            )
    )


### Régions

Pour avoir les détails d'une région, utilisez la méthode `regions()`.

Les champs disponibles en retour sont :

| Nom du champ | Type | Descriptif |
| --- | --- | --- |
| code | string | Code de la région |
| nom | string | Nom de la région |

Les champs autorisés pour la recherche sont :

| Nom du champ |
| --- |
| code |
| nom |

Exemple de recherche de la région dont le code est "94" :

```php
$GeoApiFr = new \Phelium\Component\GeoApiFr;
$datas = $GeoApiFr
    ->regions()
    ->fields(array('code', 'nom'))
    ->search('code', '94');
```

Retour :

    Array
    (
        [status_code] => 200
        [status_msg] => OK
        [url] => https://geo.api.gouv.fr/regions?code=94&fields=code,nom
        [datas] => Array
            (
                [0] => Array
                    (
                        [code] => 94
                        [nom] => Corse
                    )

            )
    )

