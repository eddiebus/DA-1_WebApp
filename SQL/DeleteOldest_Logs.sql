DELETE FROM [dbo].[Logs]
WHERE [TimeSent] in (
SELECT TOP 5 [TimeSent] FROM [dbo].[Logs]
ORDER BY [TimeSent] ASC );