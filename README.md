Warehouse project target:
 Да се разработи система за управление на бизнес свързан с логистика. Система ще
представлява административен панел, който предоставя възможност на група от
потребители да управляват важните процеси в логистиката.
Софтуерът включва:
● Създаване и управление на потребителски роли (админ, модератор,
потребител, според различните нива на достъп)
● Създаване на продукти и на категории за съответните продукти
● Възможност за управление на процесът на движение на стоките
● Създаване на доклади
Първоначални таблици:
● Products (name, description, category_id, barcode, qr_code, img, supplier_id)
● product_categories (name, description)
● Location ( name, description)
● Location_detail ( location_id, shelf, row, cell)
● Warehouse (product, location_detail_id, qty,) // каква наличност има в склада и
локацията й
● Suppliers (name, phone, e_mail, description)
● Product_activity (product, qty_expected, qty_arrived, qty_damaged, order_id,
stock_origin(null= supplier, || location_id, location_id)
● Order ( order_date, expected_receive_date, supplier)
● Order details ( qty, products, order_id)
● Warehouse_1 -> Shelf_1 -> row_1 -> cell_b // не е таблица (примерна локация)
