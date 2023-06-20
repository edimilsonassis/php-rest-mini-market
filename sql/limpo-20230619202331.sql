/*
PostgreSQL Backup
Database: minimarket/public
Backup Time: 2023-06-19 20:23:32
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
DROP FUNCTION IF EXISTS "public"."update_values_on_sale_when_add_sales_items()";
DROP FUNCTION IF EXISTS "public"."update_values_sales_items()";
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
  "sls_ite_id_sale" int4 NOT NULL,
  "sls_ite_price_total" float4 NOT NULL,
  "sls_ite_tax_total" float4 NOT NULL
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
CREATE OR REPLACE FUNCTION "update_values_on_sale_when_add_sales_items"()
  RETURNS "pg_catalog"."trigger" AS $BODY$BEGIN
	
	UPDATE 
		sales 
	SET 
		sls_total_price = ( SELECT SUM ( sls_ite_price_total ) FROM sales_items WHERE sls_ite_id_sale = NEW.sls_ite_id_sale ),
		sls_total_price_taxes = ( SELECT SUM ( sls_ite_tax_total ) FROM sales_items WHERE sls_ite_id_sale = NEW.sls_ite_id_sale ) 
	WHERE
		sls_id = NEW.sls_ite_id_sale;
		
	RETURN NEW;
	
 END$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "update_values_on_sale_when_add_sales_items"() OWNER TO "root";
CREATE OR REPLACE FUNCTION "update_values_sales_items"()
  RETURNS "pg_catalog"."trigger" AS $BODY$ BEGIN

	SELECT
		prd_name,
		prd_price,
		prd_price * NEW.sls_ite_qtd,
		tpo_tax,
		(tpo_tax / 100) * prd_price * NEW.sls_ite_qtd	
	INTO 
		NEW.sls_ite_description,
		NEW.sls_ite_price,
		NEW.sls_ite_price_total,
		NEW.sls_ite_tax,
		NEW.sls_ite_tax_total
	FROM
		products
	JOIN 
		products_types ON tpo_id = prd_id_type 			
	WHERE
		prd_id = NEW.sls_ite_id_product;
 
	RETURN NEW;
	
END;

$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION "update_values_sales_items"() OWNER TO "root";
BEGIN;
LOCK TABLE "public"."products" IN SHARE MODE;
DELETE FROM "public"."products";
COMMIT;
BEGIN;
LOCK TABLE "public"."products_types" IN SHARE MODE;
DELETE FROM "public"."products_types";
COMMIT;
BEGIN;
LOCK TABLE "public"."sales" IN SHARE MODE;
DELETE FROM "public"."sales";
COMMIT;
BEGIN;
LOCK TABLE "public"."sales_items" IN SHARE MODE;
DELETE FROM "public"."sales_items";
COMMIT;
BEGIN;
LOCK TABLE "public"."users" IN SHARE MODE;
DELETE FROM "public"."users";
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
CREATE TRIGGER "trigger_update_values_on_sale_when_add_sales_items" AFTER INSERT OR UPDATE ON "sales_items"
FOR EACH ROW
EXECUTE PROCEDURE "public"."update_values_on_sale_when_add_sales_items"();
CREATE TRIGGER "trigger_update_values_sales_items" BEFORE INSERT OR UPDATE ON "sales_items"
FOR EACH ROW
EXECUTE PROCEDURE "public"."update_values_sales_items"();
ALTER SEQUENCE "products_id_seq"
OWNED BY "products"."prd_id";
SELECT setval('"products_id_seq"', 1, false);
ALTER SEQUENCE "products_id_seq" OWNER TO "root";
ALTER SEQUENCE "sales_id_ite_seq"
OWNED BY "sales_items"."sls_ite_id";
SELECT setval('"sales_id_ite_seq"', 1, false);
ALTER SEQUENCE "sales_id_ite_seq" OWNER TO "root";
ALTER SEQUENCE "sales_id_seq"
OWNED BY "sales"."sls_id";
SELECT setval('"sales_id_seq"', 1, false);
ALTER SEQUENCE "sales_id_seq" OWNER TO "root";
ALTER SEQUENCE "type_id_seq"
OWNED BY "products_types"."tpo_id";
SELECT setval('"type_id_seq"', 1, false);
ALTER SEQUENCE "type_id_seq" OWNER TO "root";
ALTER SEQUENCE "users_id_seq"
OWNED BY "sales_items"."sls_ite_id";
SELECT setval('"users_id_seq"', 1, false);
ALTER SEQUENCE "users_id_seq" OWNER TO "root";
