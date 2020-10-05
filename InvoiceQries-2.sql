select * from Receipts where InvoiceHeaderID=69

select * from InvoiceHeader

select * from InvoiceLines

select * from ServiceHeader where ServiceHeaderID=1

select top 1 r.ReceiptDate,r.InvoiceHeaderID,r.ReferenceNumber,il.Amount InvoiceAmount ,r.Amount paid,ih.CustomerID,c.CustomerName,s.ServiceName,rm.ReceiptMethodName ReceiptMethod 
from Receipts r 
inner join InvoiceHeader ih on r.InvoiceHeaderID=ih.InvoiceHeaderID 
inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
inner join Customer c on ih.CustomerID=c.CustomerID
inner join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
inner join Services s on sh.ServiceID=s.ServiceID
INNER join ReceiptMethod rm on r.ReceiptMethodID=rm.ReceiptMethodID
where r.InvoiceHeaderID=69


select distinct sh.ServiceHeaderID, ih.InvoiceHeaderID, ih.CustomerID,ih.InvoiceDate,c.CustomerName,s.ServiceName,ih.Paid from InvoiceHeader ih
		inner join Customer c on ih.CustomerID=c.CustomerID
		inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
		inner join ServiceHeader sh on il.ServiceHeaderID=il.ServiceHeaderID
		inner join Services s on sh.ServiceID=s.ServiceID

		select * from Receipts

		select * from ReceiptMethod




