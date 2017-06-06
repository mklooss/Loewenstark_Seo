Loewenstark_Seo
=====================

Facts
------
- Version: check [config.xml](https://github.com/mklooss/Loewenstark_Seo/blob/master/app/code/community/Loewenstark/Seo/etc/config.xml)
- Extension key: Loewenstark_Seo
- [Extension on GitHub](https://github.com/mklooss/Loewenstark_Seo/)
- Composer name: loewenstark/seo on [packages.firegento.com](http://packages.firegento.com/)

Description
------------
* Redirect disabled products and categories to parent cagegory (Default: Disabled)
* Phrases: For products and categorys without Meta titles and descriptions get one from a list (See Backend Configuration)
* Maintenance the robots.txt from backend; Set different robots.txt's for every store view; Work out of the box without any .htaccess or nginx rule (See Backend Configuration)
* Category, if the URL is exactly the same as the category url, the Canonical Tag will be set. otherwise we set the robots tag to NOINDEX, FOLLOW, and no Canonical Tag
* Contacts: Add Robots-Tag and Breadcrump
* Canonical-Url for CMS, Contacts, Index Pages
* Canonical-Url in HTTP-Header
* Automatically cut Meta-Title to 55 characters (See Backend Configuration)
* Add Category-Attributes from backend
* Frontend Sitemap (shop.domain/catalog/seo_sitemap/category): Add Robots-Tag
* Account Area: Add Robots-Tag (NOINDEX/FOLLOW)
* Cart: Add Robots-Tag (NOINDEX/FOLLOW)
* Robots Tag per CMS Page
* X-Robots-Tag to HTTP-Header


Requirements
------------
- PHP >= 5.3.0

Compatibility
--------------
- Magento >= 1.7

Installation
-----------------------
Upload all files to in your Magento Root





Backend Configuration
---------

## Kontakt Formular
System > Konfiguration > Kontakt > Abschnitt "Kontaktieren Sie uns"   

**Robots Tag**   
Optionen für den Robots-Tag (```<meta name="robots" content="..." />```) im Head Bereich.   
Path: contacts/contacts/robots   
Values: INDEX,FOLLOW; NOINDEX,FOLLOW etc.   
Default: INDEX,FOLLOW   

**Breadcrumb-Eintrag anzeigen**   
Soll die Breadcrump im Kontakt Formular angezeigt werden?   
Path: contacts/contacts/breadcrumb   
Values: Yes, No   
Default: Yes   

## Produkte und Kategorien
System > Konfiguration > Katalog > Abschnitt "Suchmaschinen Optimierung"   

**Verwende Canonical Link Meta-Tag für Kategorien**   
Fügt das Canonical-Tag ```<link rel="canonical" href="...." />``` auf Kategorie-Seiten ein.   
Path: catalog/seo/category_canonical_tag_seo   
Values: Yes, No   
Default: Yes   

**Umleiten, wenn deaktiviert**   
Deaktivierte Kategorien oder Produkte auf ehem. Elternkategorie umleiten.   
Path: catalog/seo/redirect_if_disabled   
Values: Inactive,Products,Categories,Both   
Default: Inactive   

**Ausgeschlossene Kategorie-IDs**   
Wenn nach der Elternkategorie für eine automatische Weiterleitung gesucht wird, diese Kategorie-IDs ignorieren (komma-getrennt). Sieh auch "Umleiten, wenn deaktiviert".   
Path: catalog/seo/redirect_blacklist_category_ids   
Values: -   
Default: -   

**Meta-Title Maximallänge**   
Der Meta-Title von Kategorien und Produkten wird nach dieser Anzahl Zeichen abgeschnitten.   
Path: catalog/seo/meta_title_length   
Values: Integer   
Default: 55   

**Enable Phrases**   
Erlaubt das generieren von Meta-Daten (Title + Description) für Kategorien und Produkte, die angezeigt werden, wenn diese Angaben fehlen.   
Path: catalog/seo/phrases_enabled   
Values: Yes, No   
Default: No   

**Standard Kategorie Meta-Titel, Standard Kategorie Meta-Beschreibungen, Standard Produkt Meta-Titel, Standard Produkt Meta-Beschreibungen**   
Hat ein Produkt/Kategorie keine Meta-Description wird eine dieser Phrasen verwendet. Eine Phrase pro Zeile. {{product_name}}/{{category_name}} als Platzhalter für den Produktnamen/Kategorienamen verwenden.   
Path: catalog/seo/category_meta_title_phrases, catalog/seo/category_meta_description_phrases, catalog/seo/product_meta_title_phrases, catalog/seo/product_meta_description_phrases   
Values: -   
Default: -   

**robots.txt**   
Dieser Inhalt wird angezeigt, wenn http(s)://domain.com/robots.txt aufgerufen wird. Magento Short-Codes, z.B. {{store direct_url=""}}, sind verfügbar. Es darf keine robots.txt per FTP auf dem Server hochgeladen sein. Je Store View lässt sich ein anderer Inhalt einfügen.   
Path: catalog/seo/robotstxt   
Values: -   
Default: See https://github.com/mklooss/Loewenstark_Seo/blob/master/app/code/community/Loewenstark/Seo/etc/config.xml#L241-L267   

**Kategorie SEO-Text Attribute**   
Erstellt für Kategorien neue Textarea Attribute im Backend für SEO-Texte. Der Attribut-Code lautet loe_seo_text_yourcode und kann z.B. in der catalog/category/view.phtml ausgelesen werden.   
Path: catalog/seo/category_seo_text_attributes   
Values: -   
Default: -   

## Suchergebnisse
System > Konfiguration > Katalog > Abschnitt "Katalogsuche"   

**Robots Tag**   
Optionen für den Robots-Tag (```<meta name="robots" content="..." />```) im Head Bereich.   
Path: catalog/search/robots   
Values: INDEX,FOLLOW; NOINDEX,FOLLOW etc.   
Default: NOINDEX,FOLLOW   

#### Frontend Sitemap
System > Konfiguration > Katalog > Abschnitt "Sitemap"   
Die Sitemaps für Produkte und Kategorien sind hier erreichbar:   
http://shop.domain/catalog/seo_sitemap/category   
http://shop.domain/catalog/seo_sitemap/product   

**Robots Tag**   
Optionen für den Robots-Tag (```<meta name="robots" content="..." />```) im Head Bereich.   
Path: catalog/sitemap/robots   
Values: INDEX,FOLLOW; NOINDEX,FOLLOW etc.   
Default: INDEX,FOLLOW   



## Kundenbereich   
System > Konfiguration > Kundenkonfiguration > Abschnitt "Einstellungen für Suchmaschine"   

**Robots-Tag für Kundenbereich**   
Optionen für den Robots-Tag (```<meta name="robots" content="..." />```) im Head Bereich.   
Path: customer/loewenstark_seo/robots   
Values: INDEX,FOLLOW; NOINDEX,FOLLOW etc.   
Default: NOINDEX,FOLLOW   



## Warenkorb   
System > Konfiguration > Zur Kasse > Abschnitt "Warenkorb"    

**Robots-Tag**    
Optionen für den Robots-Tag (```<meta name="robots" content="..." />```) im Head Bereich.   
Path: checkout/cart/robots   
Values: INDEX,FOLLOW; NOINDEX,FOLLOW etc.   
Default: NOINDEX,FOLLOW   

Support
-------
If you encounter any problems or bugs, please create an issue on [GitHub](https://github.com/mklooss/Loewenstark_Seo/issues).

Contribution
------------
Any contribution to the development of Loewenstark_Seo is highly welcome. The best possibility to provide any code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Mathis Klooß

Licence
-------
[GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)

Copyright
---------
(c) 2013 Mathis Klooß
