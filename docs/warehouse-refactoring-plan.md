# План внедрения универсального учёта

1. Применить миграции, оставив `WAREHOUSE_USE_MOVEMENTS=false`.
2. Выполнить `php artisan warehouse:audit-legacy --csv` и сохранить созданный снимок.
3. Выполнить `php artisan warehouse:migrate-legacy`; повторный запуск создаёт только отсутствующие записи благодаря уникальной паре legacy-источника.
4. Выполнить `php artisan warehouse:verify-migration`. Строка `Расхождений по остаткам: 0` разрешает переключение; любое другое значение его блокирует.
5. Установить `WAREHOUSE_USE_MOVEMENTS=true` и очистить config cache при его использовании.

Команда переноса переносит исторические возвраты как `customer_return / Приход`. После переключения приход, ручное списание, отгрузка, отмена заказа и новые возвраты создают `warehouse_movements`. Для заказа отмена soft-delete'ит движение продажи, а повторная отгрузка восстанавливает его; повторные смены статуса не создают дубликаты. Изменение и удаление ручных движений создают reversal, исходная история сохраняется. Производство, рецептуры и инвентаризация — отдельная следующая поставка.

## Изменённые интерфейсы

- `WarehouseMovementService`: `income`, `expense`, `correct`, `reverse`, `importLegacy`.
- `WarehouseStockService`: `getBalance`, `getBalances`, `getTotals`.
- Команды: `warehouse:audit-legacy`, `warehouse:migrate-legacy`, `warehouse:verify-migration`.
