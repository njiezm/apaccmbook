# 📋 Éléments à réunir pour activer les paiements en production

> Document à remplir par les responsables de l'APACC-M.
> Objectif : rassembler **tous les identifiants et informations** nécessaires
> pour mettre en service les moyens de paiement de la plateforme e-Livre.
>
> ⚠️ Ces informations sont **confidentielles** (clés secrètes, coordonnées bancaires).
> À transmettre par un canal sécurisé, jamais par SMS ni message public.

La plateforme gère **6 moyens de paiement**. Vous n'êtes **pas obligés de tous les activer** :
cochez ceux que l'association souhaite proposer, et ne remplissez que ceux-là.

- [ ] HelloAsso   - [ ] Stripe   - [ ] PayPal   - [ ] SumUp   - [ ] Virement bancaire   - [ ] Chèque

**Site en production :** `https://ebooks.apacc-martinique.fr/`

---

## 0. Informations générales de l'association (indispensables pour TOUS les comptes marchands)

Ces éléments sont demandés lors de la création/vérification de chaque compte (Stripe, PayPal, SumUp, HelloAsso).

| Élément | Valeur à fournir |
|---|---|
| Dénomination exacte de l'association | ________________________ |
| Numéro RNA (récépissé préfecture, format `W0000000000`) | ________________________ |
| Numéro SIRET (si l'association en a un) | ________________________ |
| Adresse du siège social | ________________________ |
| Email de contact officiel (recevra les notifications de paiement) | ________________________ |
| Téléphone de contact | ________________________ |
| Représentant légal : nom, prénom, date & lieu de naissance | ________________________ |
| Pièce d'identité du représentant (scan recto/verso) | ⬜ fournie |
| RIB / IBAN de l'association (compte qui recevra les fonds) | ________________________ |
| Statuts de l'association + PV désignant le représentant | ⬜ fournis |

---

## 1. 💙 HelloAsso (recommandé pour une association — 0 % de commission)

**Prérequis :** créer un compte association sur https://www.helloasso.com et y publier
un formulaire de paiement/vente pour la boutique e-Livre.

| Élément | Où le trouver | Valeur |
|---|---|---|
| URL du formulaire HelloAsso (par ouvrage ou général) | Page publique du formulaire | ________________________ |
| Identifiant / « slug » de l'organisation | Dans l'URL `helloasso.com/associations/<slug>` | ________________________ |
| Clé secrète du webhook (facultatif mais conseillé) | Espace HelloAsso → Notifications / API | ________________________ |

**URL de notification (webhook) à déclarer côté HelloAsso :**
`https://ebooks.apacc-martinique.fr/webhook/helloasso`

---

## 2. 💜 Stripe (carte bancaire — la plus universelle)

**Prérequis :** compte sur https://dashboard.stripe.com, activé en **mode Live**
(vérification d'identité de l'association réalisée).

| Élément | Où le trouver | Valeur |
|---|---|---|
| Clé publiable (`pk_live_…`) | Dashboard → Développeurs → Clés API | ________________________ |
| Clé secrète (`sk_live_…`) | Dashboard → Développeurs → Clés API | ________________________ |
| Secret de signature du webhook (`whsec_…`) | Dashboard → Développeurs → Webhooks | ________________________ |

**Webhook à créer côté Stripe :**
- URL : `https://ebooks.apacc-martinique.fr/webhook/stripe`
- Événement à écouter : `checkout.session.completed`

---

## 3. 💛 PayPal

**Prérequis :** compte **PayPal Business** sur https://www.paypal.com, puis une application
créée sur https://developer.paypal.com (identifiants **Live**).

| Élément | Où le trouver | Valeur |
|---|---|---|
| Client ID (Live) | developer.paypal.com → Apps & Credentials → Live | ________________________ |
| Client Secret (Live) | Idem | ________________________ |
| Mode | `live` en production (`sandbox` pour les tests) | live |
| Webhook ID | developer.paypal.com → votre app → Webhooks | ________________________ |

**Webhook à créer côté PayPal :**
- URL : `https://ebooks.apacc-martinique.fr/webhook/paypal`
- Événement à écouter : `PAYMENT.CAPTURE.COMPLETED`

---

## 4. 🩵 SumUp

**Prérequis :** compte marchand sur https://sumup.com et une clé API
(https://developer.sumup.com).

| Élément | Où le trouver | Valeur |
|---|---|---|
| Clé API (`sup_sk_…`) | developer.sumup.com → API keys | ________________________ |
| Code marchand (Merchant Code) | Tableau de bord SumUp → Profil | ________________________ |
| Secret de signature du webhook (facultatif) | Configuration webhook SumUp | ________________________ |

**Webhook à déclarer côté SumUp :**
`https://ebooks.apacc-martinique.fr/webhook/sumup` (événement `checkout.status.changed`)

---

## 5. 🏦 Virement bancaire (paiement manuel, hors ligne)

Aucun compte externe : ces informations s'affichent au client, qui vire puis
l'accès est activé manuellement par un admin après réception.

| Élément | Valeur |
|---|---|
| Titulaire du compte | ________________________ |
| IBAN | ________________________ |
| BIC / SWIFT | ________________________ |

---

## 6. ✉️ Chèque (paiement manuel, hors ligne)

| Élément | Valeur |
|---|---|
| Ordre du chèque (à qui libeller) | ________________________ |
| Adresse d'envoi du chèque | ________________________ |

---

## ✅ Checklist technique (côté hébergement, à faire côté développeur)

- [ ] Site en **HTTPS** (certificat SSL actif) — obligatoire pour tous les webhooks.
- [ ] Fichier `storage/app/payment-settings.json` renseigné via l'admin (Paramètres → Paiement).
- [ ] Chaque webhook ci-dessus **déclaré et testé** dans le tableau de bord du prestataire.
- [ ] Réaliser **un achat test réel** (petit montant) sur chaque méthode activée.
- [ ] Vérifier la réception de l'**email de confirmation** et l'**activation automatique de l'accès**.
- [ ] Mentions légales, CGV et politique de confidentialité publiées (obligatoire pour la vente).

---

### 📝 Notes / questions des responsables
_(espace libre)_
