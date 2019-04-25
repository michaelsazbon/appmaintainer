
BEGIN TRANSACTION;

UPDATE public.records SET xml_col = xml(REPLACE(CAST(xml_col as TEXT), '&#xA0;', '')) WHERE reference = :'record_reference';

COMMIT;
