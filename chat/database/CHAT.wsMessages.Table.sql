USE [databaseName]
GO
/****** Object:  Table [CHAT].[wsMessages]    Script Date: 07/05/2018 11:49:14 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [CHAT].[wsMessages](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[idUser] [int] NOT NULL,
	[avatar] [text] NOT NULL,
	[name] [varchar](20) NOT NULL,
	[msg] [nvarchar](max) NOT NULL,
	[receiver] [int] NULL,
	[posted] [varchar](20) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
ALTER TABLE [CHAT].[wsMessages]  WITH CHECK ADD  CONSTRAINT [fk_idUser] FOREIGN KEY([idUser])
REFERENCES [CHAT].[users] ([user_id])
GO
ALTER TABLE [CHAT].[wsMessages] CHECK CONSTRAINT [fk_idUser]
GO
ALTER TABLE [CHAT].[wsMessages]  WITH CHECK ADD  CONSTRAINT [fk_idUserReceiver] FOREIGN KEY([receiver])
REFERENCES [CHAT].[users] ([user_id])
GO
ALTER TABLE [CHAT].[wsMessages] CHECK CONSTRAINT [fk_idUserReceiver]
GO
