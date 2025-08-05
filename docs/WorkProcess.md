# Steps

## Learning Symfony

- Compare Symfony and Laravel
- Understand the differences and similarities
- How to create a project
- Symfony CLI
- Testing

## First modeling with DBML

- Modeling with https://dbml-editor.alswl.com/

```dbml
Table users {
  id integer
  username varchar
  role varchar
  created_at timestamp

  Note: 'User Table'
}

Table customers {
  id integer
  name varchar
  created_by int [ref: > users.id]
  created_at timestamp

  Note: 'Customer Table'
}

Table products {
  id integer [pk, increment]
  name varchar
  description text [note: 'detail of the product']
  status procducts_status
  created_by integer
  created_at timestamp
  updated_by integer
  updated_at timestamp
}

Table pricing_options {
  id int [pk, increment]
  name varchar  // e.g., "Monthly", "Yearly", "Weekly"
  duration int  // Days
  is_default boolean [default: false]
  created_by integer
  created_at timestamp
  updated_by integer
  updated_at timestamp
}

Table product_pricing {
  id int [pk, increment]
  product_id int [ref: > products.id]
  pricing_option_id int [ref: > pricing_options.id]
  price decimal(10,2)
  currency varchar [default: 'USD']
  is_active boolean [default: true]
  created_at timestamp
  updated_at timestamp

  // Unique constraint for one product/option pair
  Indexes {
    (product_id, pricing_option_id) [unique]
  }
}

Table customer_subscriptions {
  id int [primary key]
  customer_id int [ref: > customers.id]
  product_pricing_id int [ref: > product_pricing.id]
  status subs_status
  created_by integer
  created_at timestamp
  updated_by integer
  updated_at timestamp
}

Enum subs_status {
  active
  cancel
}

Enum procducts_status {
  draft
  published
  private
}

Enum product_pricing_status {
  draft
  published
  private
}

```

This helped me identify the main entities to create and revealed some uncertainties.

In the description of the take-home project, there were two elements where a compromise had to be made:

-  The term "user" is ambiguous because it does not distinguish between an admin and a customer.
   To make that distinction, a user should have a role. For simplicity, I focused only on the customer.

- Also, it wasn't clearly defined what a "product" is, which doesn’t exclude that a user can have one or more subscriptions.
  The exact nature of the subscription was not entirely known.

## Identifying business commands

- Since the project is small, the folder structure had to be simple yet organized.
  For this kind of project, where the domains are clearly identified, DDD (Domain-Driven Design) could have been an option.
  But since the app isn't very large, I opted for a lighter version of domain separation, business logic, use cases, etc.

```
src
    Application
        UseCase
    CLI
    Domain
        Entity
        Repository // Interface pour les repository
    Infrastructure
        InMemory // repository with no database only in memory
    tests
        Application
            UseCase // We test multiple scenarios with UseCase module
```

Using UseCase helps keep the project simple and makes testing easier.
The architecture is loosely inspired by (but not copying) a Laravel solution which is no longer maintained (Lucid Architecture for Laravel) and chatgpt.

## Dev source & stack
- phpstorm (+ ai autocompletion)
- docker
- google
- bing
- stackoverflow
- chatgpt:
  https://chatgpt.com/share/68922c6a-1cf4-8002-a287-41282162fd88
  https://chatgpt.com/share/68922d69-7090-8002-a250-18064a53c876
  https://chatgpt.com/share/68922dbc-dbc0-8002-aaf6-ee13428f8565
  https://chatgpt.com/share/68922dde-de20-8002-861e-a1dbfd5ed684
    
