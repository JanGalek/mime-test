Nette Web Project
=================

Zadání:
-------
V příloze nalezneš soubor products.zip v tomto zipu se nachází přes 200 souborů
.json a .xml v JSON i v XML jsou totožný data tedy si můžeš zvolit zda budeš načítat
.json soubory nebo .xml soubory. Cílem je všechny soubory (JSON nebo XML)
spojit do jednoho XML souboru dle pravidel níže.

1. To co se nachází ve vehicle->name je název kategorie 1. a 2. úrovně musí se to
   rozdělit podle znaku / např Gladiator X450 / X450 EU5 (2021) bude kategorie na
1. úrovni Gladiator X450 která bude mít podkategorii X450 EU5 (2021)
2. Poté v categories->category se nachází další úroveň kategorie a v tom ještě
   další úroveň kategorie tedy celkem vznikají 4 podúrovně (jsou tam výjimky na
   které narazíš někdy se stane, že jsou 3 podúrovně v rámci tisku tuto výjimku
   můžeš zapracovat, ale nemusíš.
3. Poté se tam nachází spare_parts což jsou produkty v kategorii a je tam jméno,
   číslo produktu, DPH, cena (pokud není uvedena generuješ 0) ostatních údajů
   si nevšímáš.
4. Může se stát že např. V souboru 1085.json a tento produkt se nachází i v
   souboru např. 27252.json např. produkt 9GQ0-191301 ve výsledným XML se
   tento produkt nebude nacházet 2x, 3x, 4x, ale pouze 1x tím že vše ostatní u něj
   dojde jen k rozšíření kategorizace (co soubor to jeden kategorizační strom)
5. Výsledný XML soubor bude odpovídat RGN schématu co najdeš na linku
   https://podpora.shoptet.cz/xml-validace/ na tomto linku najdeš i ukázkové
   XML, že vše funguje jak má tak si zvaliduj dle RNG schématu nebo využij tuto
   stránku https://www.shoptet.cz/xml-validace/ (výslední XML bude mít okolo
   20-30mb) to tam nepůjde přímo nahrát, ale pokud si vygeneruješ jen např 10
   produktů tak vůči tomu to můžeš ověřit.
6. Zpět zasíláš: Kompletní PHP/Python script. Vygenerované omezené XML a
   kompletní vygenerované XML