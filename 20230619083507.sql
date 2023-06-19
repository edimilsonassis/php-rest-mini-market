/*
PostgreSQL Backup
Database: minimarket/public
Backup Time: 2023-06-19 08:35:08
*/

DROP SEQUENCE IF EXISTS "public"."products_id_seq";
DROP SEQUENCE IF EXISTS "public"."sales_id_ite_seq";
DROP SEQUENCE IF EXISTS "public"."sales_id_seq";
DROP SEQUENCE IF EXISTS "public"."type_id_seq";
DROP SEQUENCE IF EXISTS "public"."users_id_seq";
DROP TABLE IF EXISTS "public"."products";
DROP TABLE IF EXISTS "public"."products_types";
DROP TABLE IF EXISTS "public"."sales";
DROP TABLE IF EXISTS "public"."sales_items";
DROP TABLE IF EXISTS "public"."users";
DROP FUNCTION IF EXISTS "public"."update_sale_total_price()";
CREATE SEQUENCE "products_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE SEQUENCE "sales_id_ite_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE SEQUENCE "sales_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE SEQUENCE "type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE SEQUENCE "users_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
CREATE TABLE "products" (
  "prd_id" int4 NOT NULL DEFAULT nextval('products_id_seq'::regclass),
  "prd_id_user" int4 NOT NULL,
  "prd_id_type" int4 NOT NULL,
  "prd_name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "prd_price" float4 NOT NULL
)
;
ALTER TABLE "products" OWNER TO "root";
CREATE TABLE "products_types" (
  "tpo_id" int4 NOT NULL DEFAULT nextval('type_id_seq'::regclass),
  "tpo_id_user" int4 NOT NULL,
  "tpo_description" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "tpo_tax" float4 NOT NULL
)
;
ALTER TABLE "products_types" OWNER TO "root";
CREATE TABLE "sales" (
  "sls_id" int4 NOT NULL DEFAULT nextval('sales_id_seq'::regclass),
  "sls_id_user" int4 NOT NULL,
  "sls_client" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "sls_total_price" float4 NOT NULL DEFAULT 0,
  "sls_date" timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "sls_total_price_taxes" float4 NOT NULL DEFAULT 0
)
;
ALTER TABLE "sales" OWNER TO "root";
CREATE TABLE "sales_items" (
  "sls_ite_id" int4 NOT NULL DEFAULT nextval('sales_id_ite_seq'::regclass),
  "sls_ite_id_product" int4 NOT NULL,
  "sls_ite_description" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "sls_ite_price" float4 NOT NULL,
  "sls_ite_qtd" float4 NOT NULL,
  "sls_ite_tax" float4 NOT NULL,
  "sls_ite_total" float4 NOT NULL,
  "sls_ite_id_sale" int4 NOT NULL
)
;
ALTER TABLE "sales_items" OWNER TO "root";
CREATE TABLE "users" (
  "usr_id" int4 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  "urs_username" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "urs_password" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "urs_name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;
ALTER TABLE "users" OWNER TO "root";
CREATE OR REPLACE FUNCTION "update_sale_total_price"()
  RETURNS "pg_catalog"."trigger" AS $BODY$ BEGIN
		UPDATE sales 
		SET sls_total_price = ( SELECT SUM ( sls_ite_total ) FROM sales_items WHERE sls_ite_id_sale = NEW.sls_ite_id_sale ) ,
		sls_total_price_taxes = ( SELECT SUM ( (sls_ite_tax / 100) * sls_ite_price * sls_ite_qtd ) FROM sales_items WHERE sls_ite_id_sale = NEW.sls_ite_id_sale ) 
	WHERE
		sls_id = NEW.sls_ite_id_sale;
	RETURN NEW;
	
END;

$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "update_sale_total_price"() OWNER TO "root";
BEGIN;
LOCK TABLE "public"."products" IN SHARE MODE;
DELETE FROM "public"."products";
INSERT INTO "public"."products" ("prd_id","prd_id_user","prd_id_type","prd_name","prd_price") VALUES (1, 1, 1, 'Pimentão', 8.3),(2, 1, 3, 'Ponta de Peito', 28.9),(3, 1, 2, 'Pão Francês', 32.99),(4, 1, 1, 'Rúcula', 5.45),(5, 1, 1, 'Repolho', 156.932),(6, 1, 1, 'Maracujá', 18.35);
COMMIT;
BEGIN;
LOCK TABLE "public"."products_types" IN SHARE MODE;
DELETE FROM "public"."products_types";
INSERT INTO "public"."products_types" ("tpo_id","tpo_id_user","tpo_description","tpo_tax") VALUES (1, 1, 'Hortifruti', 31.5),(2, 1, 'Limpeza', 32),(3, 1, 'Açougue', 45.34),(4, 1, 'Padaria', 25),(5, 1, 'Utensílios', 25.3),(6, 1, 'Frios', 31.12),(7, 1, 'Doces', 42);
COMMIT;
BEGIN;
LOCK TABLE "public"."sales" IN SHARE MODE;
DELETE FROM "public"."sales";
INSERT INTO "public"."sales" ("sls_id","sls_id_user","sls_client","sls_total_price","sls_date","sls_total_price_taxes") VALUES (1, 1, '42109479892', 250.92201, '2023-06-19 11:33:56.597316', 83.20514),(2, 1, 'Cliente sem CPF', 770.494, '2023-06-19 11:34:13.451562', 263.0343);
COMMIT;
BEGIN;
LOCK TABLE "public"."sales_items" IN SHARE MODE;
DELETE FROM "public"."sales_items";
INSERT INTO "public"."sales_items" ("sls_ite_id","sls_ite_id_product","sls_ite_description","sls_ite_price","sls_ite_qtd","sls_ite_tax","sls_ite_total","sls_ite_id_sale") VALUES (1, 1, 'Pimentão', 8.3, 1, 31.5, 8.3, 1),(2, 2, 'Ponta de Peito', 28.9, 1, 45.34, 28.9, 1),(3, 3, 'Pão Francês', 32.99, 1, 32, 32.99, 1),(4, 4, 'Rúcula', 5.45, 1, 31.5, 5.45, 1),(5, 5, 'Repolho', 156.932, 1, 31.5, 156.932, 1),(6, 6, 'Maracujá', 18.35, 1, 31.5, 18.35, 1),(7, 1, 'Pimentão', 8.3, 10, 31.5, 83, 2),(8, 2, 'Ponta de Peito', 28.9, 5, 45.34, 144.5, 2),(9, 3, 'Pão Francês', 32.99, 2, 32, 65.98, 2),(10, 4, 'Rúcula', 5.45, 3, 31.5, 16.35, 2),(11, 5, 'Repolho', 156.932, 2, 31.5, 313.864, 2),(12, 6, 'Maracujá', 18.35, 8, 31.5, 146.8, 2);
COMMIT;
BEGIN;
LOCK TABLE "public"."users" IN SHARE MODE;
DELETE FROM "public"."users";
INSERT INTO "public"."users" ("usr_id","urs_username","urs_password","urs_name") VALUES (1, 'edimilson', 'senha@123', 'Edimilson Assis');
COMMIT;
ALTER TABLE "products" ADD CONSTRAINT "products_pkey" PRIMARY KEY ("prd_id");
ALTER TABLE "products_types" ADD CONSTRAINT "type_pkey" PRIMARY KEY ("tpo_id");
ALTER TABLE "sales" ADD CONSTRAINT "sales_pkey" PRIMARY KEY ("sls_id");
ALTER TABLE "sales_items" ADD CONSTRAINT "sales_itens_pkey" PRIMARY KEY ("sls_ite_id");
ALTER TABLE "users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("usr_id");
ALTER TABLE "products" ADD CONSTRAINT "products_prd_id_type_fkey" FOREIGN KEY ("prd_id_type") REFERENCES "public"."products_types" ("tpo_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "products" ADD CONSTRAINT "products_prd_id_user_fkey" FOREIGN KEY ("prd_id_user") REFERENCES "public"."users" ("usr_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "products_types" ADD CONSTRAINT "products_types_tpo_id_user_fkey" FOREIGN KEY ("tpo_id_user") REFERENCES "public"."users" ("usr_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "sales" ADD CONSTRAINT "sales_sls_id_user_fkey" FOREIGN KEY ("sls_id_user") REFERENCES "public"."users" ("usr_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "sales_items" ADD CONSTRAINT "sales_items_sls_ite_id_sale_fkey" FOREIGN KEY ("sls_ite_id_sale") REFERENCES "public"."sales" ("sls_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "sales_items" ADD CONSTRAINT "sales_itens_sls_ite_id_product_fkey" FOREIGN KEY ("sls_ite_id_product") REFERENCES "public"."products" ("prd_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
CREATE TRIGGER "trigger_update_sale_total_price" AFTER INSERT OR UPDATE ON "sales_items"
FOR EACH ROW
EXECUTE PROCEDURE "public"."update_sale_total_price"();
ALTER SEQUENCE "products_id_seq"
OWNED BY "products"."prd_id";
SELECT setval('"products_id_seq"', 6, true);
ALTER SEQUENCE "products_id_seq" OWNER TO "root";
ALTER SEQUENCE "sales_id_ite_seq"
OWNED BY "sales_items"."sls_ite_id";
SELECT setval('"sales_id_ite_seq"', 12, true);
ALTER SEQUENCE "sales_id_ite_seq" OWNER TO "root";
ALTER SEQUENCE "sales_id_seq"
OWNED BY "sales"."sls_id";
SELECT setval('"sales_id_seq"', 2, true);
ALTER SEQUENCE "sales_id_seq" OWNER TO "root";
ALTER SEQUENCE "type_id_seq"
OWNED BY "products_types"."tpo_id";
SELECT setval('"type_id_seq"', 7, true);
ALTER SEQUENCE "type_id_seq" OWNER TO "root";
ALTER SEQUENCE "users_id_seq"
OWNED BY "sales_items"."sls_ite_id";
SELECT setval('"users_id_seq"', 1, true);
ALTER SEQUENCE "users_id_seq" OWNER TO "root";
