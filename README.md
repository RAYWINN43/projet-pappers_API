# Application de Gestion des Entreprises (Données KBO / Pappers)

## Présentation

Cette application permet de gérer et consulter des entreprises belges à partir d'une base de données comme le site Pappers.in.
Elle propose :

* une page d’accueil listant des entreprises
* une recherche rapide 
* une fiche détaillée entreprise
* l’ajout, la modification et la suppression d’une entreprise
* la gestion des dénominations
* l’affichage des établissements avec leur nom récupéré automatiquement

L’application est réalisée avec Symfony.

---

## Base de données

### Tables utilisées

| Table                   | Rôle                                                  |
| ----------------------- | ----------------------------------------------------- |
| `pappers_enterprise`    | Informations principales des entreprises              |
| `pappers_denomination`  | Dénominations liées aux entreprises                   |
| `pappers_establishment` | Informations des établissements                       |

---

### Structure des tables principales

#### pappers_enterprise

| Colonne          | Type   |
| ---------------- | ------ |
| id               | int    |
| EnterpriseNumber | string |
| Status           | string |
| JuridicalForm    | string |
| StartDate        | date   |

#### pappers_denomination

| Colonne            | Type   |
| ------------------ | ------ |
| id                 | int    |
| EntityNumber       | string |
| Denomination       | string |
| TypeOfDenomination | string |
| Language           | string |

#### pappers_establishment

| Colonne             | Type   |
| ------------------- | ------ |
| id                  | int    |
| EnterpriseNumber    | string |
| EstablishmentNumber | string |
| StartDate           | date   |

---

## Relations (gérées manuellement)

| Relation                     | Description               |
| ---------------------------- | ------------------------- |
| Enterprise → Denomination    | via `EntityNumber`        |
| Enterprise → Establishment   | via `EnterpriseNumber`    |
| Establishment → Denomination | via `EstablishmentNumber` |
---


### Extraction du nom des établissements

Le nom des établissements n’est pas dans `pappers_establishment`.
Il est récupéré indirectement via la table `pappers_denomination` :

---

## Fonctionnalités CRUD

### 1. Création (Create)

Route : `/enterprise/add`

* création de l'entreprise
* ajout d’une dénomination associée (nom, type, langue)

### 2. Lecture (Read)

Route : `/enterprise/{num}`

Affiche :

* informations principales de l’entreprise
* liste des dénominations
* liste des établissements + leur nom récupéré via la dénomination

### 3. Modification (Update)

Route : `/enterprise/{num}/edit`

* modification des informations de l’entreprise
* mise à jour ou création de la dénomination principale

### 4. Suppression (Delete)

Route : `/enterprise/{num}/delete`

Supprime :

* l'entreprise
* les dénominations associées
* les établissements associés

---

## Les different pages principales

| Page                   | URL                      |
| ---------------------- | ------------------------ |
| Accueil                | `/`                      |
| Recherche              | `/search?q=`             |
| Ajouter une entreprise | `/enterprise/add`        |
| Voir entreprise        | `/enterprise/{num}`      |
| Modifier entreprise    | `/enterprise/{num}/edit` |

