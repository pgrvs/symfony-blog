# Titre de niveau 1
## Titre de niveau 2
### Titre de niveau 3

Ceci est un texte 

**Ceci est un texte en gars**

*Ceci est un texte en italique*

***Text italique gras***

---

>**Notre :**  ceci est une note

Pour voir la documentation, cliquez [ici](documentation/documentation.md).

## Insérer du code

```php
function toto(): string{
    return "toto";
}
```

## Diagramme de classes

```plantuml
@startuml
skinparam classAttributeIconSize 0

class Article {
    - id : int
    - titre : string
    - contenu : text
    - createdAt : datetime
    - slug : string
    - publie : bool
}

class Categorie {
    - id : int
    - titre : string
    - slug : string
}

class Commentaire {
    - id : int
    - contenu : text
    - createdAt : datetime
}

class Auteur {
    - id : int
    - prenom : string
    - nom : string
    - pseudo : string
}

class Contact {
    - id : int
    - nom : string
    - prenom : string
    - email : string
    - objet : string
    - contenu : string
    - createdAt : datetime
}

Article"0..*"--"0..1"Categorie
Article"1..1"--"0..*"Commentaire
Commentaire"1..*"--"0..1"Auteur

@enduml
```

## Tableau

| entete1 | entete2 |
|---------|---------|
| TOTO    | TATA    |