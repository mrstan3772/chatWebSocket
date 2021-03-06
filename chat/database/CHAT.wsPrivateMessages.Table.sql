USE [databaseName]
GO
/****** Object:  Table [CHAT].[wsPrivateMessages]    Script Date: 07/05/2018 11:49:14 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [CHAT].[wsPrivateMessages](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[idUser] [int] NOT NULL,
	[avatar] [text] NOT NULL,
	[name] [varchar](20) NOT NULL,
	[msg] [nvarchar](max) NOT NULL,
	[receiver] [int] NOT NULL,
	[categoryUser] [int] NOT NULL,
	[posted] [varchar](50) NOT NULL,
	[postedHour] [varchar](50) NOT NULL,
	[currTime] [datetime] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
ALTER TABLE [CHAT].[wsPrivateMessages]  WITH CHECK ADD  CONSTRAINT [fk_idUserPrivate] FOREIGN KEY([idUser])
REFERENCES [CHAT].[users] ([user_id])
GO
ALTER TABLE [CHAT].[wsPrivateMessages] CHECK CONSTRAINT [fk_idUserPrivate]
GO
ALTER TABLE [CHAT].[wsPrivateMessages]  WITH CHECK ADD  CONSTRAINT [fk_idUserReceiverPrivate] FOREIGN KEY([receiver])
REFERENCES [CHAT].[users] ([user_id])
GO
ALTER TABLE [CHAT].[wsPrivateMessages] CHECK CONSTRAINT [fk_idUserReceiverPrivate]
GO
ALTER TABLE [CHAT].[wsPrivateMessages]  WITH CHECK ADD  CONSTRAINT [fk_typeUser] FOREIGN KEY([categoryUser])
REFERENCES [CHAT].[category] ([id])
GO
ALTER TABLE [CHAT].[wsPrivateMessages] CHECK CONSTRAINT [fk_typeUser]
GO
