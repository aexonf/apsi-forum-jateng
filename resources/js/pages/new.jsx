import { toast } from "sonner";
import axios from "axios";
import Layout from "@/components/elements/layout";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { zodResolver } from "@hookform/resolvers/zod";
import { Link } from "@inertiajs/inertia-react";
import draftToHtml from "draftjs-to-html";
import { EditorState, convertToRaw } from "draft-js";
import {
    BoldIcon,
    ChevronLeft,
    ImageIcon,
    ItalicIcon,
    LinkIcon,
    List,
    ListOrdered,
    RedoIcon,
    RemoveFormattingIcon,
    StrikethroughIcon,
    UnderlineIcon,
    UndoIcon,
} from "lucide-react";
import React, { useEffect, useState } from "react";
import { Editor } from "react-draft-wysiwyg";
import "react-draft-wysiwyg/dist/react-draft-wysiwyg.css";
import { useForm } from "react-hook-form";
import z from "zod";
import { Inertia } from "@inertiajs/inertia";

const formSchema = z.object({
    title: z.string().min(1, "Title harus di isi."),
    content: z.string().min(1, "Content harus di isi."),
});

export default function CreateForum() {
    const token = localStorage.getItem("token");
    const [data, setData] = useState(null);

    async function getData() {
        try {
            const response = await axios.get("/api/v1/user", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setData(response.data.data);
        } catch (error) {
            toast.error("Gagal mengambil data.");
        }
    }

    useEffect(() => {
        getData();
    }, []);

    const [editorState, setEditorState] = useState(EditorState.createEmpty());
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
        reset,
        setValue,
    } = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: "",
            content: "",
        },
    });
    const onEditorStateChange = function (editorState) {
        setEditorState(editorState);
        setValue(
            "content",
            draftToHtml(convertToRaw(editorState.getCurrentContent()))
        );
    };

    const uploadCallback = (file) => {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const url = reader.result;
                resolve({ data: { link: url } });
            };
            reader.onerror = (error) => {
                reject(error);
            };
        });
    };

    const submit = async (data) => {
        const { title, content } = data;
        const fData = new FormData();
        fData.append("title", title);
        fData.append("content", content);

        await axios
            .post(
                "/api/v1/forum",
                {
                    title: title,
                    content: content,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            )
            .then((res) => {
                toast.success(res.data.message);
                reset();
                setValue("content", "");
                Inertia.get(`/post/${res.data.data.id}`);
            })
            .catch((err) => {
                toast.error(err.response.data.message);
            });
    };

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 mt-2 mb-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-xl -ml-12 md:text-2xl font-semibold tracking-tight">
                    Tambah Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card className="w-full bg-white rounded-lg shadow-md p-0">
                    <CardContent className="p-4">
                        <div className="flex items-start mb-4">
                            <Avatar>
                                <AvatarImage
                                    alt="User profile picture"
                                    src={
                                        data?.img_url
                                            ? `/storage/${data?.img_url}`
                                            : "/img/avatar/avatar-4.png"
                                    }
                                />
                                <AvatarFallback></AvatarFallback>
                            </Avatar>
                            <div className="ml-2">
                                <div className="text-sm  dark:text-white font-medium">
                                    {data?.name}
                                </div>
                                <div className="text-gray-400 dark:text-gray-300 text-xs">
                                    {data?.label}
                                </div>
                            </div>
                        </div>
                        <form
                            className="flex flex-col space-y-4"
                            onSubmit={handleSubmit(submit)}
                        >
                            <div className="flex flex-col space-y-2">
                                <Label
                                    className="text-sm md:text-base font-medium"
                                    htmlFor="title"
                                >
                                    Judul
                                </Label>
                                <Input
                                    id="title"
                                    placeholder="Title"
                                    {...register("title")}
                                    value={watch("title")}
                                    className={cn(
                                        "text-sm md:text-base ",
                                        errors.title
                                            ? "border-destructive focus-visible:outline-destructive focus-visible:ring-destructive"
                                            : ""
                                    )}
                                />
                                {errors.title && (
                                    <p className="text-red-500">
                                        {errors.title?.message}
                                    </p>
                                )}
                            </div>
                            <div className="flex flex-col space-y-2">
                                <Label
                                    className="text-sm md:text-base font-medium"
                                    htmlFor="content"
                                >
                                    Konten
                                </Label>
                                <div className="bg-muted">
                                    <Editor
                                        editorState={editorState}
                                        toolbarClassName="toolbarClassName"
                                        wrapperClassName="wrapperClassName"
                                        editorClassName="editorClassName"
                                        onEditorStateChange={
                                            onEditorStateChange
                                        }
                                        value={watch("content")}
                                        toolbar={{
                                            options: [
                                                "history",
                                                "blockType",
                                                "textAlign",
                                                "colorPicker",
                                                "inline",
                                                "remove",
                                                "list",
                                                "link",
                                                "image",
                                                "fontSize",
                                            ],
                                            history: {
                                                undo: { icon: UndoIcon.src },
                                                redo: { icon: RedoIcon.src },
                                            },
                                            textAlign: {
                                                inDropdown: true,
                                                options: [
                                                    "left",
                                                    "center",
                                                    "right",
                                                    "justify",
                                                ],
                                            },
                                            inline: {
                                                className: "inline-wrapper",
                                                options: [
                                                    "bold",
                                                    "italic",
                                                    "underline",
                                                    "strikethrough",
                                                    "monospace",
                                                ],
                                                bold: { icon: BoldIcon.src },
                                                italic: {
                                                    icon: ItalicIcon.src,
                                                },
                                                underline: {
                                                    icon: UnderlineIcon.src,
                                                },
                                                strikethrough: {
                                                    icon: StrikethroughIcon.src,
                                                },
                                            },
                                            remove: {
                                                icon: RemoveFormattingIcon.src,
                                            },
                                            list: {
                                                options: [
                                                    "unordered",
                                                    "ordered",
                                                ],
                                                unordered: {
                                                    icon: List.src,
                                                },
                                                ordered: {
                                                    icon: ListOrdered.src,
                                                },
                                            },
                                            link: {
                                                defaultTargetOption: "_self",
                                                options: ["link"],
                                                link: { icon: LinkIcon.src },
                                            },

                                            image: {
                                                uploadCallback: uploadCallback,
                                                alt: {
                                                    present: true,
                                                    mandatory: false,
                                                },
                                                previewImage: true,
                                                icon: ImageIcon.src,
                                            },
                                            fontSize: {
                                                options: [
                                                    8, 9, 10, 11, 12, 14, 16,
                                                    18, 24, 30, 36, 48, 60, 72,
                                                    96,
                                                ],
                                            },
                                        }}
                                    />
                                </div>
                                {errors.content && (
                                    <p className="text-red-500">
                                        {errors.content?.message}
                                    </p>
                                )}
                            </div>
                            <Button
                                className="bg-primary text-primary-foreground"
                                type="submit"
                            >
                                Tambah Forum
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </Layout>
    );
}
