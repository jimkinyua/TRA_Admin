/****** Script for SelectTopNRows command from SSMS  ******/
select * from invoiceheader

select * from invoicelines

select * from serviceheader where serviceid=323

select * from servicecharges where serviceid=323 and financialyearid=1 and subsystemid=1


select sh.serviceheaderid, sc.ServiceID,s.servicename,sc.amount from servicecharges sc inner join 
services s on sc.serviceid=s.serviceid inner join 
serviceheader sh on sh.serviceid=s.serviceid
where s.serviceid=323 and sc.financialyearid=1 and sc.subsystemid=1 