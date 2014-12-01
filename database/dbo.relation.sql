USE [arithm3dia]
GO

/****** Object:  Table [dbo].[relation]    Script Date: 11/30/2014 7:38:38 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[relation](
	[relation_id] [int] NOT NULL,
	[term_id_relation_first] [int] NOT NULL,
	[term_id_relation_second] [int] NOT NULL,
	[relation_qty] [int] NOT NULL,
	[relation_date] [date] NULL,
 CONSTRAINT [PK_relation] PRIMARY KEY CLUSTERED 
(
	[relation_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

ALTER TABLE [dbo].[relation]  WITH CHECK ADD  CONSTRAINT [FK_relation_term] FOREIGN KEY([term_id_relation_first])
REFERENCES [dbo].[term] ([term_id])
GO

ALTER TABLE [dbo].[relation] CHECK CONSTRAINT [FK_relation_term]
GO

ALTER TABLE [dbo].[relation]  WITH CHECK ADD  CONSTRAINT [FK_relation_term1] FOREIGN KEY([term_id_relation_second])
REFERENCES [dbo].[term] ([term_id])
GO

ALTER TABLE [dbo].[relation] CHECK CONSTRAINT [FK_relation_term1]
GO


