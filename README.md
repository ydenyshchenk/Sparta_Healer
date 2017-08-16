```sh

                                    `/syhhyyysyyyssoo+//:-.`
                                    `syhhhdddddmmNNNNNNNNNmmdys+/-.`
                                    .yyyhhhhhdmmmmmmmmmmmNNNNNNNNmdhyo/-.`
                                    :yyyhyyddmdddddddddhdmdddddyhdNNNNNNmhyo/-`
                                    :yyyyyhddddddddddddddddddddy+oshdhshdmNNmdy+-
                                    /yyyyyhhdddddhddddmmmmmmmmmmy+ooydyssyyyhhy-`
                                   `/yyyyyyyyyyhddhhdddddmmmmmmmmhooosyyysssss-
                                    +yyyyyyyyyyyyhdhhhhhhhhhdhdmmmhooooooosys.
                  ..--::::::::::::::oyhhhhhhhhhhhhdddddhhhhyyyyhdmdyoooooosyo
                 .shddmmmmmmmmmmmmmmNNNNNNNNNNNmmmmmmmmmmddhhyyyyddhhhyyhhhoo               ``
                 `.-/osyhddmmmmmmmNNNNNNMMMMMMMMMMMMMMMMMNNNNNNmmmmmddddddhs+              `
                       ``.-:/+osyyhmmmmdddmNNNMMMMMMMMMMMMMMMMMMMMMMMNNNNNmmdyso+/:--.`     ```
                                `/yddhysosydmNNMMMNMNMMMMMMMMMMMMMMMMMMMNNNNmmmmmmmmddhyso/:.````
 ```   ````                     `hhhhh++/+/ohmNMMMNNMNMNNNNMMMMMMMMMMMMNNmmmmmmmmmmmmmmmmmmddyo````
``````````````````           `.:sdhhhy++++++ymNMMNNNMNNNNNMMMMMMMMMMMMMNm/:://+++ooosssssssso+:`````
```````````````````````` `.-/sdmmmdhhho++++odmNNNNNNNNNNNNMMMMMMMMMMMMMNh                     ``````
``````````````````````.:oyhdmmNNNNNmhhhysyydmmmmmmmNNNNNNNMMMMMMMMMMMMMNo                     ``````
```````````````````.:shdmmmmmmmNNNNNmdhhhhhddddmmmmNNNNNNNNNMMMMMMMMMMMNs                      `````
`````````````````.+ydddddmmmmmmmNNNNNmmdhhhhhddmmmmNNNNNNNNNNNNMMNNNNNNms                      `````
````````````````+yddddddddddmmmmmmmmmmmmdddddddmmNmNNNNNNmmNNNNNNNNNNmmmy                       ````
``````````````.shhdddddddddddmmmmmmmmmmmmdddmmmmNNNNNNNNNmmNNNNNNNmmmmdms                        ```
`````````````.syhhhhddddhdddddmmmmmmmmmmmmmmmmmNNNNNNMNNmmmNNNNmmmmmmdmm/                          `
`````````````osyhhhhdddddmmmddmmmmmmmmmmmmmmmNNNNNNMMMNmmmmmmmmmmmmmmmmd`
````````````:syhdddddhyso++//::/oyhddddmmNNNNNNNNMMMMNmmmmmmmmmmNNNNmmm/
````````````shyhhys+:.`...........-/shddmmNNNNNMMMMMNmmmmNNNNMMMMMMMNNd.
````````````:-.`````````..........```.:ydmNNNMMNNMMNmmmNNNNMMNNMMMMMMMNh`
`````````````````````````.........``````-:dNNNNNMMNmmmNNNNNNNmmmmmmmmNNN+
``````````````````````````........````````ommmmNmmmmmNNNNNNmmmmmmmmmmmmmdo-`
````````````````````````````......````````.hmmmmmmmmNNNNmmmmmmmNNNNNNNNmmmmy::.
````````..````````````````````..```````````+dmmmmmNNNNNNNNNNNNNNNNmmmmmdddddddy-`
```````...`````````````````````````````````:dmmNNNNNNNNMNNNNmmmmmmmmmmmdddddddhhyo:`
````````..``````````````````````````````./odmNmmNNNNNmmmmmmmmmmmmmmddddddddhhhhhhhhhs:`
``````...```````````````````````````-+shhhhdddmmmmmmmmmmmmmmmmmmmmmmmmdddddddhhhhhhhdNh/`` `
``````..`````````````````````````-ohdddddhhdddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmddhhhhhhhdNNh+-````
`````.`.```````````````````````.sddddddddddddddmmmmmmmmmmmmmmmmmmmmmmmmmmmmmddddhhhhhhdmNmdy/.```
```

# Sparta_Healer supposed to heal your Magento 2 install

## Installation
```
git clone git@github.com:ydenyshchenk/Sparta_Healer.git app/code/Sparta/Healer
bin/magento module:enable Sparta_Healer
bin/magento setup:upgrade
chmod -R 777 var
```

## Usage
```sh
$ php bin/magento list heal
...
 sparta:heal:attribute_sets        Heal attribute set incorrect IDs
 sparta:heal:customer_attributes   Heal customer attribute<>attribute_set links
```
