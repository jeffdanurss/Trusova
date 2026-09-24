-- =====================================================
-- Esquema BD: Relojería E-commerce (PostgreSQL)
-- =====================================================

CREATE TABLE IF NOT EXISTS brands (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    logo_url    VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS categories (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE IF NOT EXISTS products (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(150) NOT NULL,
    slug            VARCHAR(180) NOT NULL UNIQUE,
    description     TEXT,
    price           NUMERIC(10,2) NOT NULL CHECK (price >= 0),
    stock           INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
    brand_id        INTEGER REFERENCES brands(id) ON DELETE SET NULL,
    category_id     INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    image_url       VARCHAR(255),
    is_active       BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_products_brand ON products(brand_id);

CREATE TABLE IF NOT EXISTS product_images (
    id          SERIAL PRIMARY KEY,
    product_id  INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    image_url   VARCHAR(255) NOT NULL,
    sort_order  INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS users (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(120) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            VARCHAR(20) NOT NULL DEFAULT 'cliente' CHECK (role IN ('cliente','admin')),
    created_at      TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS carts (
    id          SERIAL PRIMARY KEY,
    user_id     INTEGER REFERENCES users(id) ON DELETE CASCADE,
    session_id  VARCHAR(100),
    created_at  TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS cart_items (
    id          SERIAL PRIMARY KEY,
    cart_id     INTEGER NOT NULL REFERENCES carts(id) ON DELETE CASCADE,
    product_id  INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    quantity    INTEGER NOT NULL DEFAULT 1 CHECK (quantity > 0),
    UNIQUE(cart_id, product_id)
);

CREATE TABLE IF NOT EXISTS orders (
    id                  SERIAL PRIMARY KEY,
    user_id             INTEGER REFERENCES users(id) ON DELETE SET NULL,
    total               NUMERIC(10,2) NOT NULL DEFAULT 0,
    status              VARCHAR(30) NOT NULL DEFAULT 'pendiente'
                        CHECK (status IN ('pendiente','pagado','enviado','entregado','cancelado')),
    shipping_name       VARCHAR(150),
    shipping_address    VARCHAR(255),
    shipping_city       VARCHAR(100),
    shipping_phone      VARCHAR(30),
    created_at          TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS order_items (
    id          SERIAL PRIMARY KEY,
    order_id    INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id  INTEGER REFERENCES products(id) ON DELETE SET NULL,
    product_name VARCHAR(150) NOT NULL,
    unit_price  NUMERIC(10,2) NOT NULL,
    quantity    INTEGER NOT NULL CHECK (quantity > 0)
);

CREATE TABLE IF NOT EXISTS payments (
    id              SERIAL PRIMARY KEY,
    order_id        INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    method          VARCHAR(30) NOT NULL DEFAULT 'tarjeta',
    status          VARCHAR(20) NOT NULL DEFAULT 'pendiente'
                    CHECK (status IN ('pendiente','aprobado','rechazado')),
    transaction_id  VARCHAR(100),
    amount          NUMERIC(10,2) NOT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW()
);
