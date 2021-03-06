USE [databaseName]
GO
/****** Object:  Table [CHAT].[users]    Script Date: 07/05/2018 11:49:14 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [CHAT].[users](
	[user_id] [int] IDENTITY(1,1) NOT NULL,
	[email] [varchar](50) NOT NULL,
	[username] [varchar](50) NOT NULL,
	[password] [varchar](max) NOT NULL,
	[avatar] [varchar](250) NOT NULL,
	[userCategory] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
ALTER TABLE [CHAT].[users]  WITH CHECK ADD  CONSTRAINT [fk_userCategory] FOREIGN KEY([userCategory])
REFERENCES [CHAT].[category] ([id])
GO
ALTER TABLE [CHAT].[users] CHECK CONSTRAINT [fk_userCategory]
GO
